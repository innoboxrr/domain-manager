<?php

namespace Innoboxrr\DomainManager\Models\Traits\Operations;

use Illuminate\Support\Facades\DB;
use Innoboxrr\DomainManager\Procedures\Support\RegistrarManager;

trait DomainOperations
{

    /*
    public function buildPayload()
    {

        return [];

    }

    public function updatePayload()
    {

        $this->payload = $this->buildPayload();

        return $this->save();

    }
    */

    protected function getRegistrarDriverFromProvider()
    {
        $provider = $this->provider; // relación Domain->provider
        /** @var RegistrarManager $mgr */
        $mgr = app(RegistrarManager::class);
        $driverKey = $provider->driver ?? 'aws';
        $driver = $mgr->driver($driverKey);

        $config = array_merge($provider->secrets ?? [], $provider->payload ?? [], $provider->settings ?? []);
        $driver->setConfig($config);

        return $driver;
    }

    /**
     * Compra/registro del dominio en el proveedor asociado y actualiza el modelo.
     * $args: ['years'=>1, 'privacy'=>bool, 'auto_renew'=>bool, 'contact'=>array]
     */
    public function purchase(array $args = []): array
    {
        return DB::transaction(function () use ($args) {

            $this->status = 'purchasing';
            $this->provider_status = 'SUBMITTED';
            $this->privacy = array_key_exists('privacy', $args) ? (bool)$args['privacy'] : ($this->privacy ?? true);
            $this->auto_renew = array_key_exists('auto_renew', $args) ? (bool)$args['auto_renew'] : ($this->auto_renew ?? true);
            $this->save();

            $driver = $this->getRegistrarDriverFromProvider();

            $op = $driver->registerDomain([
                'domain' => $this->name,
                'years' => (int)($args['years'] ?? 1),
                'privacy' => $this->privacy,
                'auto_renew' => $this->auto_renew,
                'contact' => $args['contact'] ?? [],
            ]);

            $this->provider_ref = $op['operation_id'] ?? $this->provider_ref;
            $this->provider_status = $op['status'] ?? $this->provider_status;
            $this->save();

            return [
                'domain_id' => $this->id,
                'status' => $this->status,
                'provider_status' => $this->provider_status,
                'provider_ref' => $this->provider_ref,
            ];
        });
    }

    /**
     * Sincroniza el estado de la operación con el proveedor (SUCCESS/PENDING/FAILED).
     */
    public function syncOperation(): array
    {
        $driver = $this->getRegistrarDriverFromProvider();

        $op = $driver->fetchOperation($this->provider_ref ?? '');

        if (($op['status'] ?? '') === 'SUCCESS') {
            $this->status = 'active';
            $this->provider_status = 'SUCCESS';
            $this->save();
        } else {
            $this->provider_status = $op['status'] ?? 'PENDING';
            $this->save();
        }

        return [
            'domain_id' => $this->id,
            'status' => $this->status,
            'provider_status' => $this->provider_status,
        ];
    }

    /**
     * Asegura hosted zone (si aplica) y UPSERT de registros DNS
     * $records: ver contrato del driver
     */
    public function upsertRecords(array $records): bool
    {
        $driver = $this->getRegistrarDriverFromProvider();

        $hz = $driver->ensureHostedZone($this->name);
        $driver->upsertRecords($hz['zone_id'] ?? $this->name, $records);

        // Persistencia “representativa” local en domain_dns
        foreach ($records as $rec) {
            $this->dns()->create([
                'type' => strtoupper($rec['type']),
                'name' => $rec['name'],
                'value' => isset($rec['alias'])
                    ? json_encode($rec['alias'])
                    : (is_array($rec['value']) ? implode(',', $rec['value']) : (string)$rec['value']),
                'ttl' => (int)($rec['ttl'] ?? 300),
                'priority' => null,
            ]);
        }

        return true;
    }

}
