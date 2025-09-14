<?php

namespace Innoboxrr\DomainManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Innoboxrr\Traits\MetaOperations;
use Innoboxrr\Traits\ModelAppendsTrait;
use Innoboxrr\DomainManager\Models\Traits\Relations\DomainDnsRelations;
use Innoboxrr\DomainManager\Models\Traits\Storage\DomainDnsStorage;
use Innoboxrr\DomainManager\Models\Traits\Assignments\DomainDnsAssignment;
use Innoboxrr\DomainManager\Models\Traits\Operations\DomainDnsOperations;
use Innoboxrr\DomainManager\Models\Traits\Mutators\DomainDnsMutators;

class DomainDns extends Model
{
    use HasFactory,
        SoftDeletes,
        MetaOperations,
        ModelAppendsTrait,
        DomainDnsRelations,
        DomainDnsStorage,
        DomainDnsAssignment,
        DomainDnsOperations,
        DomainDnsMutators;

    protected $fillable = [
        'type',
        'name',
        'value',
        'ttl',
        'priority',
        'domain_id',
    ];

    protected $creatable = [
        'type',
        'name',
        'value',
        'ttl',
        'priority',
        'domain_id',
    ];

    protected $updatable = [
        'type',
        'name',
        'value',
        'ttl',
        'priority',
    ];

    protected $casts = [
        'ttl' => 'integer',
        'priority' => 'integer',
    ];

    protected $protected_metas = [];

    protected $editable_metas = [
        // metas editables si aplican
    ];

    public static $export_cols = [
        'id',
        'type',
        'name',
        'value',
        'ttl',
        'priority',
        'domain_id',
        'created_at',
    ];

    public static $loadable_relations = [
        'domain',
    ];

    public static $loadable_counts = [
        //
    ];

    /*
    protected static function newFactory()
    {
        return \Innoboxrr\DomainManager\Database\Factories\DomainDnsFactory::new();
    }
    */
}
