<?php

namespace Innoboxrr\DomainManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Innoboxrr\Traits\MetaOperations;
use Innoboxrr\Traits\ModelAppendsTrait;
use Innoboxrr\DomainManager\Models\Traits\Relations\DomainSubscriptionRelations;
use Innoboxrr\DomainManager\Models\Traits\Storage\DomainSubscriptionStorage;
use Innoboxrr\DomainManager\Models\Traits\Assignments\DomainSubscriptionAssignment;
use Innoboxrr\DomainManager\Models\Traits\Operations\DomainSubscriptionOperations;
use Innoboxrr\DomainManager\Models\Traits\Mutators\DomainSubscriptionMutators;

class DomainSubscription extends Model
{
    use HasFactory,
        SoftDeletes,
        MetaOperations,
        ModelAppendsTrait,
        DomainSubscriptionRelations,
        DomainSubscriptionStorage,
        DomainSubscriptionAssignment,
        DomainSubscriptionOperations,
        DomainSubscriptionMutators;

    protected $fillable = [
        'status',
        'renewal_cycle',
        'renewal_unit',
        'auto_renewal',
        'start_date',
        'end_date',
        'cancel_at_end_date',
        'domain_id',
        'user_id',
        'domain_payment_method_id',
    ];

    protected $creatable = [
        'status',
        'renewal_cycle',
        'renewal_unit',
        'auto_renewal',
        'start_date',
        'end_date',
        'cancel_at_end_date',
        'domain_id',
        'user_id',
        'domain_payment_method_id',
    ];

    protected $updatable = [
        'status',
        'renewal_cycle',
        'renewal_unit',
        'auto_renewal',
        'start_date',
        'end_date',
        'cancel_at_end_date',
        'domain_payment_method_id',
    ];

    protected $casts = [
        'renewal_unit' => 'integer',
        'auto_renewal' => 'boolean',
        'cancel_at_end_date' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    protected $protected_metas = [];

    protected $editable_metas = [
        // metas editables si aplican
    ];

    public static $export_cols = [
        'id',
        'status',
        'renewal_cycle',
        'renewal_unit',
        'auto_renewal',
        'start_date',
        'end_date',
        'cancel_at_end_date',
        'domain_id',
        'user_id',
        'domain_payment_method_id',
        'created_at',
    ];

    public static $loadable_relations = [
        'domain',
        'user',
        'paymentMethod',
        'renewals',
    ];

    public static $loadable_counts = [
        'renewals',
    ];

    /*
    protected static function newFactory()
    {
        return \Innoboxrr\DomainManager\Database\Factories\DomainSubscriptionFactory::new();
    }
    */
}
