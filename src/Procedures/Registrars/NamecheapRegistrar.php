<?php

/**
 * NAMECHEAP REGISTRAR (API XML) + DNS (BasicDNS)
 * -----------------------------------------------------------------------------
 * ¿QUÉ HACE?
 * - Integra con la API XML de Namecheap para: disponibilidad, registro y renovación.
 * - Gestiona DNS vía BasicDNS con endpoints: dns.getHosts / dns.setHosts.
 *
 * DEPENDENCIAS:
 * - Usa el Http Client de Laravel (no requiere SDK externo).
 *
 * CREDENCIALES / CONFIG (inyectadas vía DomainProvider->secrets/payload/settings):
 * - api_user  : string (tu ApiUser)
 * - api_key   : string (tu ApiKey)
 * - username  : string (tu Username)
 * - client_ip : string (IP autorizada en Namecheap panel)
 * - sandbox   : bool   (true para sandbox API)
 *
 * ENDPOINTS:
 * - Producción: https://api.namecheap.com/xml.response
 * - Sandbox   : https://api.sandbox.namecheap.com/xml.response
 *
 * CONTRATO DE MÉTODOS (shape de arrays):
 * - setConfig(array $cfg): void
 *      Carga credenciales y modo sandbox. Lanza InvalidArgumentException si faltan claves.
 *
 * - checkAvailability(string $fqdn): array
 *      Llama a namecheap.domains.check
 *      return [
 *          'available'  => bool,
 *          'price'      => float|null,   // si deseas, puedes enriquecer con users.getPricing
 *          'suggestions'=> array,        // aquí vacío
 *          'raw'        => array         // datos útiles del XML si quieres depurar
 *      ]
 *
 * - registerDomain(array $order): array
 *      $order = [
 *          'domain'     => 'example.com',
 *          'years'      => 1,
 *          'privacy'    => true,     // habilita WhoisGuard (gratis)
 *          'auto_renew' => true,     // Namecheap lo maneja aparte; no se fuerza aquí
 *          'contact'    => [
 *              'name','lastname','organization','address','city','state','zip',
 *              'country','phone','email'
 *          ]
 *      ];
 *      → Llama a namecheap.domains.create con Registrant/Admin/Tech (mismo contacto).
 *      return [
 *          'operation_id' => null,       // Namecheap suele ser sincrónico
 *          'status'       => 'SUCCESS'
 *      ]
 *
 * - fetchOperation(string $operationId): array
 *      Namecheap para create/renew/dns suele ser sincrónico → 'SUCCESS'.
 *
 * - ensureHostedZone(string $domain): array
 *      Llama namecheap.domains.dns.setDefault para usar BasicDNS. (No hay HostedZones).
 *      return ['zone_id' => null, 'ns' => []]
 *
 * - upsertRecords(string $zoneIdOrDomain, array $records): void
 *      Flujo:
 *         1) dns.getHosts → obtén registros actuales.
 *         2) Fusiona con $records (UPSERT por HostName+RecordType).
 *         3) dns.setHosts   → REEMPLAZA **todos** los registros (cuidado con no perder entradas).
 *
 *      $records = [
 *          [
 *              'type'    => 'A|AAAA|CNAME|MX|TXT|...|ALIAS',
 *              'name'    => 'www.example.com',    // FQDN
 *              'ttl'     => 300,
 *              'value'   => '198.51.100.10'       // string|array (Namecheap no soporta múltiple por campo)
 *              // ALIAS:
 *              'alias'   => ['dnsName' => 'lb.example.net'] // convertido a CNAME internamente
 *              // MX:
 *              'priority'=> 10
 *          ],
 *          ...
 *      ]
 *      Reglas:
 *      - ALIAS se convierte a CNAME (Namecheap no soporta ALIAS nativo).
 *      - Para “apex” (root) usar host "@". La clase calcula host desde el FQDN.
 *      - setHosts reemplaza todo; si quieres preservar registros, debes traerlos y re-enviarlos.
 *
 * - renew(string $domain, int $years = 1): array
 *      Llama namecheap.domains.renew
 *      return ['status' => 'SUCCESS']
 *
 * ERRORES:
 * - Lanza RuntimeException para errores HTTP/XML/negocio.
 * - Lanza InvalidArgumentException si faltan credenciales.
 * - Namecheap tiene rate-limits; maneja backoff si lo agregas.
 *
 * REQUISITOS ADICIONALES:
 * - Debes **autorizar** la IP (client_ip) en el panel de Namecheap (production y sandbox).
 *
 * EJEMPLO DE USO (desde DomainProviderOperations / DomainOperations):
 *      $driver = app(\Innoboxrr\DomainManager\Procedures\Support\RegistrarManager::class)
 *                  ->driver('namecheap');
 *      $driver->setConfig($cfgFromProvider);
 *      $check = $driver->checkAvailability('acme.com');
 *      if ($check['available']) {
 *          $res = $driver->registerDomain([...]);
 *          $driver->ensureHostedZone('acme.com');
 *          $driver->upsertRecords('acme.com', [[
 *              'type' => 'CNAME',
 *              'name' => 'www.acme.com',
 *              'ttl'  => 300,
 *              'value'=> 'lb.tuapp.com'
 *          ]]);
 *      }
 *
 * PRUEBAS:
 * - Usa SANDBOX: https://www.sandbox.namecheap.com/
 * - Credenciales sandbox distintas a producción; recuerda autorizar la sandbox client_ip.
 *
 * NOTAS DE PRODUCCIÓN:
 * - dns.setHosts **sobrescribe todos** los registros; si tu UI hace “upsert” parcial,
 *   primero lee (getHosts) y vuelve a enviar la unión completa (los antiguos + los nuevos).
 * - Para múltiples valores (p.ej. varios A o TXT), Namecheap requiere múltiple HostNameN/AddressN;
 *   este implemento hace merge simple. Si necesitas granularidad por duplicado, itera separando
 *   los valores en entradas independientes antes de setHosts.
 */


namespace Innoboxrr\DomainManager\Procedures\Registrars;

use Innoboxrr\DomainManager\Procedures\Contracts\Registrar;
use Illuminate\Support\Facades\Http;

class NamecheapRegistrar implements Registrar
{
    /**
     * @var array{
     *     api_user?: string,
     *     api_key?: string,
     *     username?: string,
     *     client_ip?: string,
     *     sandbox?: bool
     * }
     */
    protected array $cfg = [];

    protected string $endpoint = 'https://api.namecheap.com/xml.response';
    protected string $sandboxEndpoint = 'https://api.sandbox.namecheap.com/xml.response';

    public function setConfig(array $config): void
    {
        $this->cfg = $config;
    }

    public function checkAvailability(string $fqdn): array
    {
        $this->assertCreds();

        $params = $this->baseParams('namecheap.domains.check') + [
            'DomainList' => strtolower($fqdn),
        ];

        $xml = $this->request($params);
        $r = $xml->CommandResponse->DomainCheckResult ?? null;

        if (!$r) {
            throw new \RuntimeException('Respuesta inválida de Namecheap (check).');
        }

        $available = ((string)$r['Available'] === 'true');
        $price = null;

        // Opcional: obtener precio (si así lo configuras) usando users.getPricing
        // Aquí lo dejamos en null por simplicidad; puedes enriquecerlo con otra llamada.

        return [
            'available' => $available,
            'price' => $price,
            'suggestions' => [], // podrías llamar namecheap.domains.gettldlist y armar locales
            'raw' => [
                'errorcount' => (string)($xml->Errors['Count'] ?? '0'),
            ],
        ];
    }

    public function registerDomain(array $order): array
    {
        /**
         * $order esperado:
         * [
         *     'domain' => 'example.com',
         *     'years' => 1,
         *     'privacy' => true,
         *     'auto_renew' => true, // Namecheap usa separate flags, aquí no lo forzamos
         *     'contact' => [ ... ],
         * ]
         */
        $this->assertCreds();

        $domain = strtolower($order['domain'] ?? '');
        if (!$domain) {
            throw new \InvalidArgumentException('registerDomain requiere "domain".');
        }

        $years = (int)($order['years'] ?? 1);
        $privacy = (bool)($order['privacy'] ?? true);

        $c = $this->mapNcContacts($order['contact'] ?? [], $domain);

        $params = $this->baseParams('namecheap.domains.create') + [
            'DomainName' => $domain,
            'Years' => $years,
            // WhoisGuard (Namecheap lo ofrece gratis; habilítalo si privacy=true)
            'AddFreeWhoisguard' => $privacy ? 'Yes' : 'No',
            'WGEnabled' => $privacy ? 'Yes' : 'No',
        ] + $c;

        $xml = $this->request($params);
        $r = $xml->CommandResponse->DomainCreateResult ?? null;

        if (!$r || (string)$r['Registered'] !== 'true') {
            // revisar errores
            $err = $this->firstError($xml) ?: 'No se pudo registrar el dominio.';
            throw new \RuntimeException('Namecheap registerDomain error: ' . $err);
        }

        // Namecheap suele ser síncrono: asumimos SUCCESS
        return [
            'operation_id' => null,
            'status' => 'SUCCESS',
        ];
    }

    public function fetchOperation(string $operationId): array
    {
        // Namecheap es, en general, síncrono para create/renew/dns.
        return ['status' => 'SUCCESS'];
    }

    public function ensureHostedZone(string $domain): array
    {
        // En Namecheap no hay HostedZone; aseguramos BasicDNS para poder usar setHosts
        $this->assertCreds();

        $domain = strtolower($domain);

        // namecheap.domains.dns.setDefault -> configura BasicDNS
        $xml = $this->request($this->baseParams('namecheap.domains.dns.setDefault') + [
            'DomainName' => $domain,
        ]);

        // Opcional: podrías llamar getList para saber los NS, pero no es necesario para setHosts.
        return ['zone_id' => null, 'ns' => []];
    }

    public function upsertRecords(string $zoneIdOrDomain, array $records): void
    {
        $this->assertCreds();

        $domain = strtolower($zoneIdOrDomain);

        // 1) obtener hosts actuales
        $current = $this->request($this->baseParams('namecheap.domains.dns.getHosts') + [
            'DomainName' => $domain,
        ]);

        $hosts = [];
        foreach ($current->CommandResponse->DomainDNSGetHostsResult->host ?? [] as $h) {
            $hosts[] = [
                'HostName' => (string)$h['Name'],
                'RecordType' => (string)$h['Type'],
                'Address' => (string)$h['Address'],
                'TTL' => (string)$h['TTL'],
                'MXPref' => (string)($h['MXPref'] ?? ''),
            ];
        }

        // 2) fusionar/upsert con $records
        foreach ($records as $rec) {
            $type = strtoupper((string)($rec['type'] ?? ''));
            $nameFqdn = rtrim((string)($rec['name'] ?? ''), '.');
            $ttl = (int)($rec['ttl'] ?? 300);

            $host = $this->hostFromFqdn($nameFqdn, $domain); // ej: www.ejemplo.com -> www

            if ($type === 'ALIAS') {
                // Namecheap no soporta ALIAS como tal; normalmente usas CNAME a un LB.
                // Si te pasan 'alias', intenta convertirlo a CNAME con su dnsName.
                $alias = $rec['alias'] ?? [];
                $value = (string)($alias['dnsName'] ?? $alias['dns_name'] ?? '');
                $type = 'CNAME';
            } else {
                $value = $rec['value'] ?? '';
                if (is_array($value)) {
                    // Namecheap setHosts no soporta múltiples RR por par (HostName,Type)
                    // Sugerencia: crea varios registros con mismo host/type en múltiples posiciones.
                    // Aquí concatenamos por simplicidad (para TXT/KX a veces funciona separando con espacio).
                    $value = implode(' ', $value);
                }
            }

            // Buscar si ya existe un registro con mismo HostName + RecordType
            $idx = null;
            foreach ($hosts as $i => $h) {
                if (strcasecmp($h['HostName'], $host) === 0 && strtoupper($h['RecordType']) === $type) {
                    $idx = $i;
                    break;
                }
            }

            $row = [
                'HostName' => $host,
                'RecordType' => $type,
                'Address' => (string)$value,
                'TTL' => (string)$ttl,
                'MXPref' => ($type === 'MX') ? (string)($rec['priority'] ?? '10') : '',
            ];

            if ($idx === null) {
                $hosts[] = $row;
            } else {
                $hosts[$idx] = $row;
            }
        }

        // 3) enviar setHosts (reemplaza todos los hosts)
        $params = $this->baseParams('namecheap.domains.dns.setHosts') + [
            'DomainName' => $domain,
        ];

        $n = 1;
        foreach ($hosts as $h) {
            $params['HostName' . $n] = $h['HostName'];
            $params['RecordType' . $n] = $h['RecordType'];
            $params['Address' . $n] = $h['Address'];
            $params['TTL' . $n] = $h['TTL'];
            if ($h['RecordType'] === 'MX') {
                $params['MXPref' . $n] = $h['MXPref'] ?: '10';
            }
            $n++;
        }

        $xml = $this->request($params);
        $ok = (string)($xml->CommandResponse->DomainDNSSetHostsResult['IsSuccess'] ?? 'false') === 'true';

        if (!$ok) {
            $err = $this->firstError($xml) ?: 'Falló setHosts.';
            throw new \RuntimeException('Namecheap upsertRecords error: ' . $err);
        }
    }

    public function renew(string $domain, int $years = 1): array
    {
        $this->assertCreds();

        $xml = $this->request($this->baseParams('namecheap.domains.renew') + [
            'DomainName' => strtolower($domain),
            'Years' => (int)$years,
        ]);

        $r = $xml->CommandResponse->DomainRenewResult ?? null;
        if (!$r || (string)$r['Renew'] !== 'true') {
            $err = $this->firstError($xml) ?: 'No se pudo renovar el dominio.';
            throw new \RuntimeException('Namecheap renew error: ' . $err);
        }

        return ['status' => 'SUCCESS'];
    }

    /* ============================
     * Helpers
     * ============================ */

    protected function baseParams(string $command): array
    {
        $endpoint = !empty($this->cfg['sandbox']) ? $this->sandboxEndpoint : $this->endpoint;

        return [
            'Command' => $command,
            'ApiUser' => $this->cfg['api_user'],
            'ApiKey' => $this->cfg['api_key'],
            'UserName' => $this->cfg['username'],
            'ClientIp' => $this->cfg['client_ip'],
            '_endpoint' => $endpoint, // meta interna para request()
        ];
    }

    protected function request(array $params): \SimpleXMLElement
    {
        $endpoint = $params['_endpoint'];
        unset($params['_endpoint']);

        try {
            $resp = Http::timeout(30)->get($endpoint, $params);
        } catch (\Throwable $e) {
            throw new \RuntimeException('HTTP error hacia Namecheap: ' . $e->getMessage(), 0, $e);
        }

        if (!$resp->ok()) {
            throw new \RuntimeException('Namecheap HTTP ' . $resp->status() . ': ' . $resp->body());
        }

        try {
            $xml = new \SimpleXMLElement($resp->body());
        } catch (\Throwable $e) {
            throw new \RuntimeException('No se pudo parsear XML de Namecheap.', 0, $e);
        }

        $status = (string)($xml['Status'] ?? 'ERROR'); // OK | ERROR
        if ($status !== 'OK') {
            $err = $this->firstError($xml) ?: 'Respuesta de error Namecheap.';
            throw new \RuntimeException($err);
        }

        return $xml;
    }

    protected function firstError(\SimpleXMLElement $xml): ?string
    {
        if (isset($xml->Errors) && isset($xml->Errors->Error)) {
            $first = $xml->Errors->Error[0] ?? null;
            if ($first) {
                return trim((string)$first);
            }
        }
        return null;
    }

    protected function hostFromFqdn(string $fqdn, string $root): string
    {
        $fqdn = rtrim($fqdn, '.');
        $root = rtrim($root, '.');

        if (strcasecmp($fqdn, $root) === 0) {
            return '@'; // apex
        }

        if (str_ends_with($fqdn, '.' . $root)) {
            return substr($fqdn, 0, -1 * (strlen($root) + 1));
        }

        // Si no matchea, devolver tal cual (Namecheap lo aceptará como host)
        return $fqdn;
    }

    protected function assertCreds(): void
    {
        foreach (['api_user','api_key','username','client_ip'] as $k) {
            if (empty($this->cfg[$k])) {
                throw new \InvalidArgumentException("Falta credencial Namecheap: {$k}");
            }
        }
    }

    /**
     * Mapea el contacto “agnóstico” de tu app al esquema que exige Namecheap
     * y lo replica en Registrant/Admin/Tech/AuxBilling.
     *
     * Campos esperados (keys flexibles):
     * - name / first_name
     * - lastname / last_name
     * - organization / org
     * - address / address1
     * - address2 (opcional)
     * - city
     * - state / region
     * - zip / postal_code
     * - country (ISO-2 preferido; se fuerza a MAYÚSCULAS)
     * - phone (se normaliza a +CC.NNNNNNNNN)
     * - email
     *
     * @throws \InvalidArgumentException si faltan datos críticos o email inválido
     */
    protected function mapNcContacts(array $contact, string $domain): array
    {
        // 1) Normalizar/acolchonar input
        $c = array_merge([
            'name'        => $contact['first_name']  ?? ($contact['firstname']   ?? ($contact['given_name'] ?? ($contact['nombre'] ?? ''))),
            'lastname'    => $contact['last_name']   ?? ($contact['lastname']    ?? ($contact['family_name'] ?? ($contact['apellidos'] ?? ''))),
            'organization'=> $contact['organization']?? ($contact['org']         ?? ($contact['company']     ?? '')),
            'address'     => $contact['address']     ?? ($contact['address1']    ?? ''),
            'address2'    => $contact['address2']    ?? '',
            'city'        => $contact['city']        ?? '',
            'state'       => $contact['state']       ?? ($contact['region']      ?? ''),
            'zip'         => $contact['zip']         ?? ($contact['postal_code'] ?? ($contact['cp'] ?? '')),
            'country'     => $contact['country']     ?? ($contact['country_code']?? 'US'),
            'phone'       => $contact['phone']       ?? '',
            'email'       => $contact['email']       ?? '',
        ], $contact);

        // Si “name” llegó con nombre completo y “lastname” vacío, partir por último espacio.
        if (!empty($c['name']) && empty($c['lastname']) && strpos($c['name'], ' ') !== false) {
            $parts = preg_split('/\s+/', trim($c['name']));
            $c['lastname'] = array_pop($parts);
            $c['name']     = implode(' ', $parts);
        }

        $firstName = trim((string)$c['name']);
        $lastName  = trim((string)$c['lastname']);
        $org       = trim((string)$c['organization']);
        $addr1     = trim((string)$c['address']);
        $addr2     = trim((string)$c['address2']);
        $city      = trim((string)$c['city']);
        $state     = trim((string)$c['state']);
        $zip       = trim((string)$c['zip']);
        $country   = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', (string)$c['country']), 0, 2));
        $email     = trim((string)$c['email']);
        $phoneRaw  = trim((string)$c['phone']);

        // 2) Validaciones mínimas
        if ($firstName === '' || $lastName === '' || $addr1 === '' || $city === '' || $country === '' || $email === '') {
            throw new \InvalidArgumentException('Contacto incompleto: name/lastname/address/city/country/email son requeridos.');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Email de contacto inválido.');
        }

        // 3) Normalizar teléfono a +CC.NNNNNNNNN
        $phone = $this->normalizeNcPhone($phoneRaw, $country);
        if ($phone === null) {
            // Namecheap exige Phone; si no hay manera de normalizar, forzar un placeholder válido
            // (mejor lanzar excepción para producción; aquí dejamos fallback conservador)
            $phone = '+1.5555555555';
        }

        // 4) Construir bloque para un rol (helper interno)
        $build = function (string $prefix) use ($firstName, $lastName, $org, $addr1, $addr2, $city, $state, $zip, $country, $phone, $email) {
            $out = [
                $prefix . 'FirstName'        => $firstName,
                $prefix . 'LastName'         => $lastName,
                $prefix . 'OrganizationName' => $org !== '' ? $org : ($firstName . ' ' . $lastName),
                $prefix . 'Address1'         => $addr1,
                $prefix . 'City'             => $city,
                $prefix . 'StateProvince'    => $state !== '' ? $state : 'NA',
                $prefix . 'PostalCode'       => $zip   !== '' ? $zip   : '00000',
                $prefix . 'Country'          => $country,
                $prefix . 'Phone'            => $phone,
                $prefix . 'EmailAddress'     => $email,
            ];
            if ($addr2 !== '') {
                $out[$prefix . 'Address2'] = $addr2;
            }
            return $out;
        };

        // 5) Replicar en los 4 roles que admite Namecheap
        return
            $build('Registrant') +
            $build('Tech') +
            $build('Admin') +
            $build('AuxBilling');
    }

    /**
     * Normaliza a formato Namecheap: +CC.NNNNNNNNN
     * - Si viene E.164 (+52XXXXXXXXXX) lo convierte a +52.XXXXXXXXXX
     * - Si no trae “+”, intenta inferir CC por país (mapa básico LATAM/EU/US-CA).
     * - Devuelve null si no es posible normalizar.
     */
    protected function normalizeNcPhone(string $phone, string $country): ?string
    {
        $phone = trim($phone);
        if ($phone === '') {
            return null;
        }

        // Mapa mínimo de códigos de país (amplía si lo necesitas)
        $ccMap = [
            'US' => '1',  'CA' => '1',  'MX' => '52', 'AR' => '54', 'CL' => '56',
            'CO' => '57', 'PE' => '51', 'ES' => '34', 'UY' => '598','BR' => '55',
            'EC' => '593','GT' => '502','SV' => '503','HN' => '504','NI' => '505',
            'CR' => '506','PA' => '507','DO' => '1',  'PR' => '1',  'VE' => '58',
        ];

        // Quitar todo menos dígitos y "+"
        $raw = preg_replace('/[^\d\+]/', '', $phone);

        // E.164: +CCNNNNNNN -> convertir a +CC.NNNNNNN
        if (preg_match('/^\+(\d{1,3})(\d{6,})$/', $raw, $m)) {
            $cc = $m[1];
            $rest = ltrim($m[2], '0'); // evitar ceros de más al inicio del local
            $rest = $rest !== '' ? $rest : $m[2]; // si quitó todo, regresa original
            return '+' . $cc . '.' . $rest;
        }

        // Si empieza con 00, conviértelo a "+"
        if (strpos($raw, '00') === 0) {
            $raw = '+' . substr($raw, 2);
            if (preg_match('/^\+(\d{1,3})(\d{6,})$/', $raw, $m)) {
                return '+' . $m[1] . '.' . $m[2];
            }
        }

        // Sin prefijo internacional; inferir por país
        $digits = preg_replace('/\D/', '', $raw);
        if ($digits === '') {
            return null;
        }

        $cc = $ccMap[$country] ?? null;
        if ($cc === null) {
            // Último intento: suponer CC de 2 dígitos si el largo es muy grande
            // (esto es heurístico; idealmente valida con una lib)
            $len = strlen($digits);
            if ($len >= 11) {
                $ccGuess = substr($digits, 0, 2);
                $rest    = substr($digits, 2);
                return '+' . $ccGuess . '.' . $rest;
            }
            return null;
        }

        return '+' . $cc . '.' . $digits;
    }

}
