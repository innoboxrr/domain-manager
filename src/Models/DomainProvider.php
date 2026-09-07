<?php

namespace Innoboxrr\DomainManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Innoboxrr\Traits\MetaOperations;
use Innoboxrr\Traits\ModelAppendsTrait;
use Innoboxrr\DomainManager\Models\Traits\Relations\DomainProviderRelations;
use Innoboxrr\DomainManager\Models\Traits\Storage\DomainProviderStorage;
use Innoboxrr\DomainManager\Models\Traits\Assignments\DomainProviderAssignment;
use Innoboxrr\DomainManager\Models\Traits\Operations\DomainProviderOperations;
use Innoboxrr\DomainManager\Models\Traits\Mutators\DomainProviderMutators;

class DomainProvider extends Model
{
    use HasFactory,
        SoftDeletes,
        MetaOperations,
        ModelAppendsTrait,
        DomainProviderRelations,
        DomainProviderStorage,
        DomainProviderAssignment,
        DomainProviderOperations,
        DomainProviderMutators;

    protected $fillable = [
        'workspace_id',
        'name',
        'driver',
        'secrets',
        'settings',
        'payload',
    ];

    protected $creatable = [
        'workspace_id',
        'name',
        'driver',
        'secrets',
        'settings',
        'payload',
    ];

    protected $updatable = [
        'workspace_id',
        'name',
        'driver',
        'secrets',
        'settings',
        'payload',
    ];

    protected $casts = [
        'workspace_id' => 'integer',
        'payload' => 'array',
        'secrets' => 'encrypted:array',
        'settings' => 'array',
    ];

    protected $protected_metas = [];

    protected $editable_metas = [
        // metas editables si aplican
    ];

    public static $export_cols = [
        'id',
        'name',
        'created_at',
    ];

    public static $loadable_relations = [
        'domains',
        'providerPayments',
        // 'metas' si tienes modelo Meta
    ];

    public static $loadable_counts = [
        'domains',
        'providerPayments',
    ];

    /*
    protected static function newFactory()
    {
        return \Innoboxrr\DomainManager\Database\Factories\DomainProviderFactory::new();
    }
    */
}
