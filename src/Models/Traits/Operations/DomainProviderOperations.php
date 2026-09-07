<?php

namespace Innoboxrr\DomainManager\Models\Traits\Operations;

use Illuminate\Support\Arr;
use Innoboxrr\DomainManager\Procedures\Contracts\Registrar;
use Innoboxrr\DomainManager\Procedures\Support\RegistrarManager;
use Throwable;

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

    public function getRegistrarDriver(): Registrar
    {
        /** @var RegistrarManager $mgr */
        $mgr = app(RegistrarManager::class);
        $driverKey = $this->driver ?? 'aws';
        $driver = $mgr->driver($driverKey);

        $driver->setConfig($this->providerConfig());

        return $driver;
    }

    public function checkAvailability(string $fqdn): array
    {
        $driver = $this->getRegistrarDriver();
        return $driver->checkAvailability(strtolower($fqdn));
    }

    protected function providerConfig(): array
    {
        $driverKey = $this->driver ?? 'aws';

        $secrets = $this->sanitizeArray($this->secrets ?? []);
        $settings = $this->sanitizeArray($this->settings ?? []);
        $payload = $this->sanitizeArray($this->payload ?? []);

        switch ($driverKey) {
            case 'namecheap':
                $config = [
                    'api_user' => $this->normalizeString(Arr::get($secrets, 'api_user')),
                    'api_key' => $this->normalizeString(Arr::get($secrets, 'api_key')),
                    'username' => $this->normalizeString(Arr::get($secrets, 'username')),
                    'client_ip' => $this->normalizeString(Arr::get($secrets, 'client_ip')),
                    'sandbox' => $this->normalizeBoolean(Arr::get($settings, 'sandbox')),
                    'base_url' => $this->normalizeString(Arr::get($payload, 'base_url')),
                    'sandbox_base_url' => $this->normalizeString(Arr::get($payload, 'sandbox_base_url')),
                ];
                break;
            default:
                $config = [
                    'access_key' => $this->normalizeString(Arr::get($secrets, 'access_key')),
                    'secret_key' => $this->normalizeString(Arr::get($secrets, 'secret_key')),
                    'region' => $this->normalizeString(Arr::get($payload, 'region')),
                    'hosted_zone_id' => $this->normalizeString(Arr::get($payload, 'hosted_zone_id')),
                    'role_arn' => $this->normalizeString(Arr::get($payload, 'role_arn')),
                ];
                break;
        }

        $merged = array_merge($config ?? [], $secrets, $settings, $payload);

        return $this->filterNullValues($merged);
    }

    protected function sanitizeArray(array $values): array
    {
        foreach ($values as $key => $value) {
            if (is_array($value)) {
                $values[$key] = $this->sanitizeArray($value);
                continue;
            }

            if (is_string($value)) {
                $value = trim($value);
                $values[$key] = $value === '' ? null : $value;
                continue;
            }

            $values[$key] = $value;
        }

        return $values;
    }

    public function testConnection(): array
    {
        try {
            $driver = $this->getRegistrarDriver();
            $driver->checkAvailability('seguropro-connection-test.com');

            return [
                'ok' => true,
            ];
        } catch (Throwable $exception) {
            return [
                'ok' => false,
                'error' => $exception->getMessage(),
            ];
        }
    }

    protected function normalizeString($value): ?string
    {
        if (is_null($value)) {
            return null;
        }

        if (is_string($value)) {
            $value = trim($value);
        }

        return $value === '' ? null : (string) $value;
    }

    protected function normalizeBoolean($value): ?bool
    {
        if (is_null($value)) {
            return null;
        }

        if (is_bool($value)) {
            return $value;
        }

        if (is_string($value)) {
            $value = trim($value);
        }

        if ($value === '') {
            return null;
        }

        $filtered = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        return is_null($filtered) ? (bool) $value : $filtered;
    }

    protected function filterNullValues(array $config): array
    {
        return Arr::where($config, static fn ($value) => !is_null($value));
    }
}
