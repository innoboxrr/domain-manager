<?php

namespace Innoboxrr\DomainManager\Models\Traits\Relations;

use Innoboxrr\DomainManager\Models\Domain;
use Innoboxrr\DomainManager\Models\DomainPaymentMethod;
use Innoboxrr\DomainManager\Models\DomainRenewal;

trait DomainSubscriptionRelations
{
    public function domain()
    {
        return $this->belongsTo(Domain::class, 'domain_id');
    }

    public function user()
    {
        $userClass = config('domain-manager.user_class', 'App\\Models\\User');
        return $this->belongsTo($userClass, 'user_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(DomainPaymentMethod::class, 'domain_payment_method_id');
    }

    public function renewals()
    {
        return $this->hasMany(DomainRenewal::class, 'domain_subscription_id');
    }
}
