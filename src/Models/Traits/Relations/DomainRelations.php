<?php

namespace Innoboxrr\DomainManager\Models\Traits\Relations;

use Innoboxrr\DomainManager\Models\DomainTld;
use Innoboxrr\DomainManager\Models\DomainProvider;
use Innoboxrr\DomainManager\Models\DomainContact;
use Innoboxrr\DomainManager\Models\DomainDns;
use Innoboxrr\DomainManager\Models\DomainSubscription;
use Innoboxrr\DomainManager\Models\DomainRenewal;
use Innoboxrr\DomainManager\Models\DomainPayment;

trait DomainRelations
{
    public function tld()
    {
        return $this->belongsTo(DomainTld::class, 'domain_tld_id');
    }

    public function provider()
    {
        return $this->belongsTo(DomainProvider::class, 'domain_provider_id');
    }

    public function contacts()
    {
        return $this->hasMany(DomainContact::class, 'domain_id');
    }

    public function dns()
    {
        return $this->hasMany(DomainDns::class, 'domain_id');
    }

    public function subscription()
    {
        return $this->hasOne(DomainSubscription::class, 'domain_id');
    }

    public function renewals()
    {
        return $this->hasManyThrough(
            DomainRenewal::class,
            DomainSubscription::class,
            'domain_id',               // FK en DomainSubscription -> domains.id
            'domain_subscription_id',  // FK en DomainRenewal -> domain_subscriptions.id
            'id',
            'id'
        );
    }

    public function payments()
    {
        return $this->hasManyThrough(
            DomainPayment::class,
            DomainRenewal::class,
            'domain_subscription_id',  // FK en DomainRenewal -> domain_subscriptions.id
            'domain_renewal_id',       // FK en DomainPayment -> domain_renewals.id
            optional($this->subscription)->id ? 'id' : 'id',
            'id'
        );
    }
}
