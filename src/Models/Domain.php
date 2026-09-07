<?php

namespace Innoboxrr\DomainManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Innoboxrr\Traits\MetaOperations;
use Innoboxrr\Traits\ModelAppendsTrait;
use Innoboxrr\DomainManager\Models\Traits\Relations\DomainRelations;
use Innoboxrr\DomainManager\Models\Traits\Storage\DomainStorage;
use Innoboxrr\DomainManager\Models\Traits\Assignments\DomainAssignment;
use Innoboxrr\DomainManager\Models\Traits\Operations\DomainOperations;
use Innoboxrr\DomainManager\Models\Traits\Mutators\DomainMutators;

class Domain extends Model
{
    use HasFactory,
        SoftDeletes,
        MetaOperations,
        ModelAppendsTrait,
        DomainRelations,
        DomainStorage,
        DomainAssignment,
        DomainOperations,
        DomainMutators;

    protected $fillable = [
        'name',
        'status',
        'domain_tld_id',
        'domain_provider_id',
        'provider_ref',
        'provider_status',
        'nameservers',
        'privacy',
        'auto_renew',
        'expires_at',
    ];

    protected $creatable = [
        'name',
        'status',
        'domain_tld_id',
        'domain_provider_id',
        'provider_ref',
        'provider_status',
        'nameservers',
        'privacy',
        'auto_renew',
        'expires_at',
    ];

    protected $updatable = [
        'name',
        'status',
        'domain_tld_id',
        'domain_provider_id',
        'provider_ref',
        'provider_status',
        'nameservers',
        'privacy',
        'auto_renew',
        'expires_at',
    ];

    protected $casts = [
        'nameservers' => 'array',
        'privacy' => 'boolean',
        'auto_renew' => 'boolean',
        'expires_at' => 'datetime',
    ];

    protected $protected_metas = [];

    protected $editable_metas = [
        // metas editables si aplican
    ];

    public static $export_cols = [
        'id',
        'name',
        'status',
        'domain_tld_id',
        'domain_provider_id',
        'created_at',
    ];

    public static $loadable_relations = [
        'tld',
        'provider',
        'contacts',
        'dns',
        'subscription',
        'subscription.paymentMethod',
        'subscription.renewals',
        'subscription.renewals.payments',
        'subscription.renewals.payments.paymentMethod',
        'subscription.renewals.payments.providerPayments',
        'subscription.renewals.payments.providerPayments.provider',
    ];

    public static $loadable_counts = [
        'dns',
        'contacts',
        'subscription.renewals',
    ];

    /*
    protected static function newFactory()
    {
        return \Innoboxrr\DomainManager\Database\Factories\DomainFactory::new();
    }
    */
}
