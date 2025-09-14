<?php

namespace Innoboxrr\DomainManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Innoboxrr\Traits\MetaOperations;
use Innoboxrr\Traits\ModelAppendsTrait;
use Innoboxrr\DomainManager\Models\Traits\Relations\DomainRenewalRelations;
use Innoboxrr\DomainManager\Models\Traits\Storage\DomainRenewalStorage;
use Innoboxrr\DomainManager\Models\Traits\Assignments\DomainRenewalAssignment;
use Innoboxrr\DomainManager\Models\Traits\Operations\DomainRenewalOperations;
use Innoboxrr\DomainManager\Models\Traits\Mutators\DomainRenewalMutators;

class DomainRenewal extends Model
{
    use HasFactory,
        SoftDeletes,
        MetaOperations,
        ModelAppendsTrait,
        DomainRenewalRelations,
        DomainRenewalStorage,
        DomainRenewalAssignment,
        DomainRenewalOperations,
        DomainRenewalMutators;

    protected $fillable = [
        'type',
        'renewed_date',
        'status',
        'next_due_date',
        'notes',
        'domain_subscription_id',
    ];

    protected $creatable = [
        'type',
        'renewed_date',
        'status',
        'next_due_date',
        'notes',
        'domain_subscription_id',
    ];

    protected $updatable = [
        'type',
        'status',
        'next_due_date',
        'notes',
    ];

    protected $casts = [
        'renewed_date' => 'datetime',
        'next_due_date' => 'datetime',
    ];

    protected $protected_metas = [];

    protected $editable_metas = [
        // metas editables si aplican
    ];

    public static $export_cols = [
        'id',
        'type',
        'status',
        'renewed_date',
        'next_due_date',
        'domain_subscription_id',
        'created_at',
    ];

    public static $loadable_relations = [
        'subscription',
        'payments',
    ];

    public static $loadable_counts = [
        'payments',
    ];

    /*
    protected static function newFactory()
    {
        return \Innoboxrr\DomainManager\Database\Factories\DomainRenewalFactory::new();
    }
    */
}
