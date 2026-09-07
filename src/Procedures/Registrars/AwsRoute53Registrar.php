<?php

/**
 * AWS ROUTE 53 (DOMAINS + DNS) REGISTRAR
 * -----------------------------------------------------------------------------
 * ¿QUÉ HACE?
 * - Integra con AWS Route53Domains para: disponibilidad, compra/renovación de dominios.
 * - Integra con AWS Route53 (Hosted Zones) para: creación/obtención de zona y UPSERT de DNS.
 *
 * DEPENDENCIAS:
 * - Composer: "aws/aws-sdk-php": "^3"
 *
 * CREDENCIALES / CONFIG (inyectadas vía DomainProvider->secrets/payload/settings):
 * - region       : string  (ej. "us-east-1")
 * - access_key   : string  (AWS Access Key ID)
 * - secret_key   : string  (AWS Secret Access Key)
 *
 * PERMISOS IAM (mínimos recomendados):
 * - route53:ChangeResourceRecordSets
 * - route53:CreateHostedZone
 * - route53:ListHostedZonesByName
 * - route53:ListResourceRecordSets
 * - route53domains:CheckDomainAvailability
 * - route53domains:RegisterDomain
 * - route53domains:GetOperationDetail
 * - route53domains:RenewDomain
 *
 * CONTRATO DE MÉTODOS (shape de arrays):
 * - setConfig(array $cfg): void
 *      Recibe las claves anteriores. Lanza RuntimeException si falta el SDK.
 *
 * - checkAvailability(string $fqdn): array
 *      return [
 *          'available'  => bool,
 *          'price'      => float|null,   // AWS no da precio aquí (normalmente null)
 *          'suggestions'=> array,        // opcional (aquí vacío)
 *          'raw_status' => string        // AVAILABLE|TAKEN|...
 *      ]
 *
 * - registerDomain(array $order): array
 *      $order = [
 *          'domain'     => 'example.com',
 *          'years'      => 1,                 // int
 *          'privacy'    => true,              // WHOIS privacy
 *          'auto_renew' => true,
 *          'contact'    => [                  // admin/registrant/tech (mismo contacto)
 *              'name','lastname','organization','address','city','state','zip',
 *              'country','phone','email'
 *          ],
 *          'nameservers'=> ['ns1.example.net','ns2.example.net'] // opcional
 *      ];
 *      return [
 *          'operation_id' => 'aws-op-123',    // para hacer polling
 *          'status'       => 'SUBMITTED'
 *      ]
 *
 * - fetchOperation(string $operationId): array
 *      return [
 *          'status'     => 'SUCCESS|PENDING|FAILED',
 *          'raw_status' => 'SUCCESSFUL|IN_PROGRESS|ERROR|SUBMITTED'
 *      ]
 *
 * - ensureHostedZone(string $domain): array
 *      Crea (si no existe) o recupera la hosted zone pública del dominio raíz.
 *      return [
 *          'zone_id' => '/hostedzone/Z123...',
 *          'ns'      => ['ns-xxx.awsdns-..', ...]
 *      ]
 *
 * - upsertRecords(string $zoneIdOrDomain, array $records): void
 *      $records = [
 *          [
 *              'type'  => 'CNAME|A|TXT|MX|ALIAS',
 *              'name'  => 'www.example.com',
 *              'ttl'   => 300,                // omitido si es ALIAS
 *              'value' => 'lb.example.net'    // string|array (no aplica en ALIAS)
 *              // ALIAS:
 *              'alias' => [
 *                  'hostedZoneId' => 'Z2FDTNDATAQYW2',   // p.ej CloudFront HZ ID
 *                  'dnsName'      => 'd111111abcdef8.cloudfront.net',
 *                  'evaluateTargetHealth' => false
 *              ]
 *          ],
 *          ...
 *      ]
 *      NOTA: Para ALIAS se envía como registro tipo A con AliasTarget.
 *
 * - renew(string $domain, int $years = 1): array
 *      return [
 *          'status'       => 'SUBMITTED',
 *          'operation_id' => 'aws-op-456'
 *      ]
 *
 * FORMATO DE CONTACTO AWS (IMPORTANTE):
 * - 'PhoneNumber' DEBE llevar prefijo internacional y un punto: "+52.5555555555"
 * - 'CountryCode' debe ir en ISO 3166-1 alpha-2 (e.g., "MX", "US").
 *
 * ERRORES:
 * - Lanza RuntimeException en errores de SDK o HTTP.
 * - Lanza InvalidArgumentException si faltan campos requeridos.
 *
 * EJEMPLO DE USO (desde DomainProviderOperations / DomainOperations):
 *      $driver = app(\Innoboxrr\DomainManager\Procedures\Support\RegistrarManager::class)
 *                  ->driver('aws');
 *      $driver->setConfig($cfgFromProvider);
 *      $check = $driver->checkAvailability('acme.com');
 *      if ($check['available']) {
 *          $op = $driver->registerDomain([...]);
 *      }
 *
 * PRUEBAS:
 * - AWS no tiene sandbox para dominios: usa mocks (PHPUnit) o compra dominios baratos.
 * - Se recomienda abstraer el cliente del SDK para poder “fakearlo”.
 *
 * NOTAS DE PRODUCCIÓN:
 * - Crear HostedZone no delega automáticamente NS en tu registrador; si el dominio fue
 *   comprado fuera de Route53, necesitarás apuntar NS desde el registrador.
 * - Rate limits: manejar reintentos exponenciales si añades polling o lotes de cambios.
 */


namespace Innoboxrr\DomainManager\Procedures\Registrars;

use Innoboxrr\DomainManager\Procedures\Contracts\Registrar;

class AwsRoute53Registrar implements Registrar
{
    /**
     * @var array{
     *     region?: string,
     *     access_key?: string,
     *     secret_key?: string
     * }
     */
    protected array $cfg = [];

    /** @var \Aws\Route53\Route53Client|null */
    protected $r53 = null;

    /** @var \Aws\Route53Domains\Route53DomainsClient|null */
    protected $r53d = null;

    public function setConfig(array $config): void
    {
        $this->cfg = $config;

        if (!class_exists(\Aws\Sdk::class)) {
            throw new \RuntimeException('AWS SDK no está instalado. Requiere aws/aws-sdk-php.');
        }

        $region = $this->cfg['region'] ?? 'us-east-1';
        $key = $this->cfg['access_key'] ?? null;
        $secret = $this->cfg['secret_key'] ?? null;

        $base = [
            'version' => '2013-04-01',
            'region' => $region,
        ];

        $creds = ($key && $secret) ? ['credentials' => ['key' => $key, 'secret' => $secret]] : [];

        $this->r53 = new \Aws\Route53\Route53Client(array_merge($base, $creds));
        $this->r53d = new \Aws\Route53Domains\Route53DomainsClient(array_merge(['version' => '2014-05-15', 'region' => $region], $creds));
    }

    public function checkAvailability(string $fqdn): array
    {
        try {
            $res = $this->r53d->checkDomainAvailability([
                'DomainName' => strtolower($fqdn),
            ]);
            $status = (string)($res->get('Availability') ?? 'UNKNOWN'); // AVAILABLE | TAKEN | ...
            $available = ($status === 'AVAILABLE');

            // AWS no devuelve precio aquí. Si los quieres, puedes consultarlos de otra fuente (pivot TLD/proveedor).
            return [
                'available' => $available,
                'price' => null,
                'suggestions' => [], // opcional: podrías armar sugerencias locales
                'raw_status' => $status,
            ];
        } catch (\Throwable $e) {
            throw new \RuntimeException('AWS checkAvailability error: ' . $e->getMessage(), 0, $e);
        }
    }

    public function registerDomain(array $order): array
    {
        /**
         * $order esperado:
         * [
         *     'domain' => 'example.com',
         *     'years' => 1,
         *     'privacy' => true,
         *     'auto_renew' => true,
         *     'contact' => [
         *         'name','lastname','organization','address','city','state','zip','country','phone','email'
         *     ],
         *     // opcional:
         *     'nameservers' => ['ns-1.example.net', 'ns-2.example.net']
         * ]
         */
        $domain = strtolower($order['domain'] ?? '');
        if (!$domain) {
            throw new \InvalidArgumentException('registerDomain requiere "domain".');
        }

        try {
            $years = (int)($order['years'] ?? 1);
            $privacy = (bool)($order['privacy'] ?? true);
            $autoRenew = (bool)($order['auto_renew'] ?? true);
            $contact = $this->mapAwsContact($order['contact'] ?? []);

            $params = [
                'DomainName' => $domain,
                'DurationInYears' => $years,
                'AdminContact' => $contact,
                'RegistrantContact' => $contact,
                'TechContact' => $contact,
                'AutoRenew' => $autoRenew,
                'PrivacyProtectAdminContact' => $privacy,
                'PrivacyProtectRegistrantContact' => $privacy,
                'PrivacyProtectTechContact' => $privacy,
            ];

            if (!empty($order['nameservers']) && is_array($order['nameservers'])) {
                $params['Nameservers'] = array_map(fn ($ns) => ['Name' => $ns], $order['nameservers']);
            }

            $res = $this->r53d->registerDomain($params);

            $opId = (string)($res->get('OperationId') ?? '');
            return [
                'operation_id' => $opId,
                'status' => 'SUBMITTED',
            ];
        } catch (\Throwable $e) {
            throw new \RuntimeException('AWS registerDomain error: ' . $e->getMessage(), 0, $e);
        }
    }

    public function fetchOperation(string $operationId): array
    {
        if (!$operationId) {
            // algunos casos (otros registradores) pueden no emitir op-id
            return ['status' => 'SUCCESS'];
        }

        try {
            $res = $this->r53d->getOperationDetail([
                'OperationId' => $operationId,
            ]);

            $status = (string)($res->get('Status') ?? 'IN_PROGRESS'); // SUBMITTED | IN_PROGRESS | ERROR | SUCCESSFUL
            $map = [
                'SUBMITTED' => 'PENDING',
                'IN_PROGRESS' => 'PENDING',
                'SUCCESSFUL' => 'SUCCESS',
                'ERROR' => 'FAILED',
            ];

            return [
                'status' => $map[$status] ?? 'PENDING',
                'raw_status' => $status,
            ];
        } catch (\Throwable $e) {
            throw new \RuntimeException('AWS fetchOperation error: ' . $e->getMessage(), 0, $e);
        }
    }

    public function ensureHostedZone(string $domain): array
    {
        $root = strtolower($domain);

        try {
            // 1) Buscar si existe
            $zones = $this->r53->listHostedZonesByName(['DNSName' => $root]);
            $hz = null;
            foreach ((array)$zones->get('HostedZones') as $z) {
                if (rtrim($z['Name'], '.') === $root) {
                    $hz = $z;
                    break;
                }
            }

            // 2) Crear si no existe
            if (!$hz) {
                $callerRef = 'dm-' . $root . '-' . uniqid();
                $create = $this->r53->createHostedZone([
                    'Name' => $root,
                    'CallerReference' => $callerRef,
                ]);
                $hz = $create->get('HostedZone');
                $delegation = $create->get('DelegationSet');
                $ns = $delegation['NameServers'] ?? [];
                return [
                    'zone_id' => $hz['Id'],
                    'ns' => $ns,
                ];
            }

            // 3) Obtener NS del HZ
            $rrs = $this->r53->listResourceRecordSets([
                'HostedZoneId' => $hz['Id'],
                'StartRecordType' => 'NS',
                'StartRecordName' => $root . '.',
                'MaxItems' => '1',
            ]);

            $ns = [];
            foreach ((array)$rrs->get('ResourceRecordSets') as $rr) {
                if ($rr['Type'] === 'NS') {
                    $ns = array_map(fn ($r) => $r['Value'], $rr['ResourceRecords']);
                    break;
                }
            }

            return [
                'zone_id' => $hz['Id'],
                'ns' => $ns,
            ];
        } catch (\Throwable $e) {
            throw new \RuntimeException('AWS ensureHostedZone error: ' . $e->getMessage(), 0, $e);
        }
    }

    public function upsertRecords(string $zoneIdOrDomain, array $records): void
    {
        try {
            // permitir pasar zone-id directo o el dominio: si es dominio, resuelve HZ
            $zoneId = str_starts_with($zoneIdOrDomain, '/hostedzone/')
                ? $zoneIdOrDomain
                : $this->ensureHostedZone($zoneIdOrDomain)['zone_id'];

            $changes = [];
            foreach ($records as $rec) {
                $type = strtoupper((string)($rec['type'] ?? ''));
                $name = rtrim((string)($rec['name'] ?? ''), '.');
                $ttl = (int)($rec['ttl'] ?? 300);

                if ($type === 'ALIAS') {
                    // ALIAS => A/AAAA con AliasTarget
                    $alias = $rec['alias'] ?? [];
                    $changes[] = [
                        'Action' => 'UPSERT',
                        'ResourceRecordSet' => [
                            'Name' => $name . '.',
                            'Type' => 'A',
                            'AliasTarget' => [
                                'HostedZoneId' => $alias['hostedZoneId'] ?? $alias['hosted_zone_id'] ?? '',
                                'DNSName' => rtrim((string)($alias['dnsName'] ?? $alias['dns_name'] ?? ''), '.') . '.',
                                'EvaluateTargetHealth' => (bool)($alias['evaluateTargetHealth'] ?? false),
                            ],
                        ],
                    ];
                } else {
                    $vals = $rec['value'] ?? '';
                    $list = is_array($vals) ? $vals : [$vals];

                    $changes[] = [
                        'Action' => 'UPSERT',
                        'ResourceRecordSet' => [
                            'Name' => $name . '.',
                            'Type' => $type,
                            'TTL' => $ttl,
                            'ResourceRecords' => array_map(fn ($v) => ['Value' => (string)$v], $list),
                        ],
                    ];
                }
            }

            // AWS permite enviar varios cambios en un solo batch
            $this->r53->changeResourceRecordSets([
                'HostedZoneId' => $zoneId,
                'ChangeBatch' => [
                    'Changes' => $changes,
                ],
            ]);
        } catch (\Throwable $e) {
            throw new \RuntimeException('AWS upsertRecords error: ' . $e->getMessage(), 0, $e);
        }
    }

    public function renew(string $domain, int $years = 1): array
    {
        try {
            $res = $this->r53d->renewDomain([
                'DomainName' => strtolower($domain),
                'DurationInYears' => $years,
            ]);

            $opId = (string)($res->get('OperationId') ?? '');
            return [
                'status' => 'SUBMITTED',
                'operation_id' => $opId,
            ];
        } catch (\Throwable $e) {
            throw new \RuntimeException('AWS renew error: ' . $e->getMessage(), 0, $e);
        }
    }

    public function getDomain(string $domain): array
    {
        try {
            $res = $this->r53d->getDomainDetail([
                'DomainName' => strtolower($domain),
            ]);

            return [
                'domain' => $domain,
                'status' => $res->get('Status') ?? null,
                'auto_renew' => (bool) ($res->get('AutoRenew') ?? false),
                'expires_at' => $res->get('Expiry') ?? null,
                'nameservers' => array_map(static fn ($ns) => (string) $ns['Name'], $res->get('Nameservers') ?? []),
            ];
        } catch (\Throwable $e) {
            throw new \RuntimeException('AWS getDomain error: ' . $e->getMessage(), 0, $e);
        }
    }

    public function listRecords(string $domain): array
    {
        try {
            $zone = $this->ensureHostedZone($domain);
            $zoneId = $zone['zone_id'];

            $records = [];
            $next = null;

            do {
                $params = ['HostedZoneId' => $zoneId];
                if ($next) {
                    $params['StartRecordName'] = $next['name'];
                    $params['StartRecordType'] = $next['type'];
                }

                $res = $this->r53->listResourceRecordSets($params);

                foreach ($res->get('ResourceRecordSets') ?? [] as $set) {
                    $records[] = [
                        'type' => (string) ($set['Type'] ?? ''),
                        'name' => rtrim((string) ($set['Name'] ?? ''), '.'),
                        'ttl' => isset($set['TTL']) ? (int) $set['TTL'] : null,
                        'value' => $this->mapAwsRecordValue($set),
                        'priority' => isset($set['ResourceRecords'][0]['Value']) && ($set['Type'] ?? '') === 'MX'
                            ? ($set['ResourceRecords'][0]['Value'] ?? null)
                            : null,
                    ];
                }

                $next = null;
                if ($res->get('IsTruncated')) {
                    $next = [
                        'name' => $res->get('NextRecordName'),
                        'type' => $res->get('NextRecordType'),
                    ];
                }
            } while ($next);

            return $records;
        } catch (\Throwable $e) {
            throw new \RuntimeException('AWS listRecords error: ' . $e->getMessage(), 0, $e);
        }
    }

    public function transferDomain(array $transfer): array
    {
        throw new \BadMethodCallException('Transfers are not supported by the AWS registrar implementation.');
    }

    public function releaseDomain(string $domain, array $payload = []): array
    {
        throw new \BadMethodCallException('Domain release is not supported by the AWS registrar implementation.');
    }

    /**
     * Mapea un contacto genérico (tu formulario) al formato AWS.
     * Requiere phone con +CC.NUMERO (p.ej. +52.5500000000).
     */
    protected function mapAwsRecordValue(array $set)
    {
        if (isset($set['AliasTarget'])) {
            return [
                'dnsName' => $set['AliasTarget']['DNSName'] ?? null,
                'hostedZoneId' => $set['AliasTarget']['HostedZoneId'] ?? null,
                'evaluateTargetHealth' => $set['AliasTarget']['EvaluateTargetHealth'] ?? false,
            ];
        }

        $records = $set['ResourceRecords'] ?? [];
        if (empty($records)) {
            return null;
        }

        if (count($records) === 1) {
            return $records[0]['Value'] ?? null;
        }

        return array_map(static fn ($record) => $record['Value'] ?? null, $records);
    }

    /**
     * Mapea un contacto genérico (tu formulario) al formato AWS.
     * Requiere phone con +CC.NUMERO (p.ej. +52.5500000000).
     */
    protected function mapAwsContact(array $c): array
    {
        $first = (string)($c['name'] ?? '');
        $last = (string)($c['lastname'] ?? '');
        $org = (string)($c['organization'] ?? '');
        $addr = (string)($c['address'] ?? '');
        $city = (string)($c['city'] ?? '');
        $state = (string)($c['state'] ?? '');
        $zip = (string)($c['zip'] ?? '');
        $country = (string)($c['country'] ?? '');
        $phone = (string)($c['phone'] ?? '');
        $email = (string)($c['email'] ?? '');

        if (!$first || !$last || !$addr || !$city || !$state || !$zip || !$country || !$phone || !$email) {
            throw new \InvalidArgumentException('Contacto AWS incompleto.');
        }

        return [
            'FirstName' => $first,
            'LastName' => $last,
            'OrganizationName' => $org ?: $first . ' ' . $last,
            'AddressLine1' => $addr,
            'City' => $city,
            'State' => $state,
            'CountryCode' => strtoupper($country),
            'ZipCode' => $zip,
            'PhoneNumber' => $phone, // formato +52.5555555555
            'Email' => $email,
        ];
    }
}
