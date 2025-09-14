<?php

namespace Innoboxrr\DomainManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Innoboxrr\Traits\MetaOperations;
use Innoboxrr\Traits\ModelAppendsTrait;
use Innoboxrr\DomainManager\Models\Traits\Relations\DomainTldRelations;
use Innoboxrr\DomainManager\Models\Traits\Storage\DomainTldStorage;
use Innoboxrr\DomainManager\Models\Traits\Assignments\DomainTldAssignment;
use Innoboxrr\DomainManager\Models\Traits\Operations\DomainTldOperations;
use Innoboxrr\DomainManager\Models\Traits\Mutators\DomainTldMutators;

class DomainTld extends Model
{
    use HasFactory,
        SoftDeletes,
        MetaOperations,
        ModelAppendsTrait,
        DomainTldRelations,
        DomainTldStorage,
        DomainTldAssignment,
        DomainTldOperations,
        DomainTldMutators;

    protected $fillable = [
        'name',
    ];

    protected $creatable = [
        'name',
    ];

    protected $updatable = [
        'name',
    ];

    protected $casts = [
        // sin json/bool
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
    ];

    public static $loadable_counts = [
        'domains',
    ];

    /*
    protected static function newFactory()
    {
        return \Innoboxrr\DomainManager\Database\Factories\DomainTldFactory::new();
    }
    */
}
