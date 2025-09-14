<?php

namespace Innoboxrr\DomainManager\Models;

use Illuminate\Database\Eloquent.Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Innoboxrr\Traits\MetaOperations;
use Innoboxrr\Traits\ModelAppendsTrait;
use Innoboxrr\DomainManager\Models\Traits\Relations\DomainPaymentRelations;
use Innoboxrr\DomainManager\Models\Traits\Storage\DomainPaymentStorage;
use Innoboxrr\DomainManager\Models\Traits\Assignments\DomainPaymentAssignment;
use Innoboxrr\DomainManager\Models\Traits\Operations\DomainPaymentOperations;
use Innoboxrr\DomainManager\Models\Traits\Mutators\DomainPaymentMutators;

class DomainPayment extends Model
{
    use HasFactory,
        SoftDeletes,
        MetaOperations,
        ModelAppendsTrait,
        DomainPaymentRelations,
        DomainPaymentStorage,
        DomainPaymentAssignment,
        DomainPaymentOperations,
        DomainPaymentMutators;

    protected $fillable = [
        'processor',
        'transaction_id',
        'amount',
        'tax',
        'domain_renewal_id',
        'domain_payment_method_id',
    ];

    protected $creatable = [
        'processor',
        'transaction_id',
        'amount',
        'tax',
        'domain_renewal_id',
        'domain_payment_method_id',
    ];

    protected $updatable = [
        'processor',
        'transaction_id',
        'amount',
        'tax',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'tax' => 'decimal:2',
    ];

    protected $protected_metas = [];

    protected $editable_metas = [
        // metas editables si aplican
    ];

    public static $export_cols = [
        'id',
        'processor',
        'transaction_id',
        'amount',
        'tax',
        'domain_renewal_id',
        'domain_payment_method_id',
        'created_at',
    ];

    public static $loadable_relations = [
        'renewal',
        'paymentMethod',
        'providerPayments',
    ];

    public static $loadable_counts = [
        'providerPayments',
    ];

    /*
    protected static function newFactory()
    {
        return \Innoboxrr\DomainManager\Database\Factories\DomainPaymentFactory::new();
    }
    */
}