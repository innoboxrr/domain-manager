<?php

namespace Innoboxrr\DomainManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Innoboxrr\Traits\MetaOperations;
use Innoboxrr\Traits\ModelAppendsTrait;
use Innoboxrr\DomainManager\Models\Traits\Relations\DomainProviderPaymentRelations;
use Innoboxrr\DomainManager\Models\Traits\Storage\DomainProviderPaymentStorage;
use Innoboxrr\DomainManager\Models\Traits\Assignments\DomainProviderPaymentAssignment;
use Innoboxrr\DomainManager\Models\Traits\Operations\DomainProviderPaymentOperations;
use Innoboxrr\DomainManager\Models\Traits\Mutators\DomainProviderPaymentMutators;

class DomainProviderPayment extends Model
{
    use HasFactory,
        SoftDeletes,
        MetaOperations,
        ModelAppendsTrait,
        DomainProviderPaymentRelations,
        DomainProviderPaymentStorage,
        DomainProviderPaymentAssignment,
        DomainProviderPaymentOperations,
        DomainProviderPaymentMutators;

    protected $fillable = [
        'status',
        'amount',
        'domain_provider_id',
        'domain_payment_id',
    ];

    protected $creatable = [
        'status',
        'amount',
        'domain_provider_id',
        'domain_payment_id',
    ];

    protected $updatable = [
        'status',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    protected $protected_metas = [];

    protected $editable_metas = [
        // metas editables si aplican
    ];

    public static $export_cols = [
        'id',
        'status',
        'amount',
        'domain_provider_id',
        'domain_payment_id',
        'created_at',
    ];

    public static $loadable_relations = [
        'provider',
        'domainPayment',
    ];

    public static $loadable_counts = [
        //
    ];

    /*
    protected static function newFactory()
    {
        return \Innoboxrr\DomainManager\Database\Factories\DomainProviderPaymentFactory::new();
    }
    */
}
