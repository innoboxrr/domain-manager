<?php

namespace Innoboxrr\DomainManager\Procedures\Contracts;

interface Registrar
{
    public function setConfig(array $config): void;

    public function checkAvailability(string $fqdn): array;
    // ['available'=>bool, 'price'=>float|null, 'suggestions'=>array]

    public function registerDomain(array $order): array;
    // $order: ['domain','years','privacy','auto_renew','contact'=>[...]]
    // return: ['operation_id'=>string|null, 'status'=>'SUBMITTED|SUCCESS|FAILED', ...]

    public function fetchOperation(string $operationId): array;
    // ['status'=>'SUCCESS|PENDING|FAILED', ...]

    public function ensureHostedZone(string $domain): array;
    // ['zone_id'=>string|null, 'ns'=>array]

    public function upsertRecords(string $zoneIdOrDomain, array $records): void;
    // $records: [['type'=>'CNAME','name'=>'www.ejemplo.com','value'=>'lb.tuapp.com','ttl'=>300, 'alias'=>array|null], ...]

    public function renew(string $domain, int $years = 1): array;

    /**
     * Obtiene los detalles actuales del dominio directamente del proveedor.
     * Debe incluir al menos estado, fecha de expiración y nameservers si están disponibles.
     */
    public function getDomain(string $domain): array;

    /**
     * Lista los registros DNS conocidos por el proveedor. Cada registro debe incluir tipo, nombre, valor, ttl y prioridad.
     */
    public function listRecords(string $domain): array;

    /**
     * Inicia o gestiona una transferencia de dominio. El payload mínimo debe contener el dominio y el auth-code requerido.
     */
    public function transferDomain(array $transfer): array;

    /**
     * Libera o cancela un dominio en el proveedor cuando la API lo permita.
     */
    public function releaseDomain(string $domain, array $payload = []): array;
}
