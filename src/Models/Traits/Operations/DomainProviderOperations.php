<?php

namespace Innoboxrr\DomainManager\Models\Traits\Operations;

use Innoboxrr\DomainManager\Procedures\Support\RegistrarManager;

trait DomainProviderOperations
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

    protected function getRegistrarDriver()
    {
        /** @var RegistrarManager $mgr */
        $mgr = app(RegistrarManager::class);
        $driverKey = $this->driver ?? 'aws';
        $driver = $mgr->driver($driverKey);

        // Combina secrets (cifrados), payload y settings
        $config = array_merge($this->secrets ?? [], $this->payload ?? [], $this->settings ?? []);
        $driver->setConfig($config);

        return $driver;
    }

    public function checkAvailability(string $fqdn): array
    {
        $driver = $this->getRegistrarDriver();
        return $driver->checkAvailability(strtolower($fqdn));
    }

}
