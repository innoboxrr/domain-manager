<?php

namespace Innoboxrr\DomainManager\Models\Traits\Relations;

use Innoboxrr\DomainManager\Models\DomainSubscription;
use Innoboxrr\DomainManager\Models\DomainPayment;

trait DomainRenewalRelations
{
    public function subscription()
    {
        return $this->belongsTo(DomainSubscription::class, 'domain_subscription_id');
    }

    public function payments()
    {
        return $this->hasMany(DomainPayment::class, 'domain_renewal_id');
    }
}
