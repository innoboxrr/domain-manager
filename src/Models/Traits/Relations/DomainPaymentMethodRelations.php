<?php

namespace Innoboxrr\DomainManager\Models\Traits\Relations;

use Innoboxrr\DomainManager\Models\DomainSubscription;
use Innoboxrr\DomainManager\Models\DomainPayment;

trait DomainPaymentMethodRelations
{
    public function user()
    {
        $userClass = config('domain-manager.user_class', 'App\\Models\\User');
        return $this->belongsTo($userClass, 'user_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(DomainSubscription::class, 'domain_payment_method_id');
    }

    public function payments()
    {
        return $this->hasMany(DomainPayment::class, 'domain_payment_method_id');
    }
}
