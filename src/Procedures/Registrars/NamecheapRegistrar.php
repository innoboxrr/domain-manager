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
}
