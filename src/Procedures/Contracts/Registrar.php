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
}
