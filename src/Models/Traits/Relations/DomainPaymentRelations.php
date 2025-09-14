<?php

namespace Innoboxrr\DomainManager\Models\Traits\Relations;

use Innoboxrr\DomainManager\Models\DomainRenewal;
use Innoboxrr\DomainManager\Models\DomainPaymentMethod;
use Innoboxrr\DomainManager\Models\DomainProviderPayment;

trait DomainPaymentRelations
{
    public function renewal()
    {
        return $this->belongsTo(DomainRenewal::class, 'domain_renewal_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(DomainPaymentMethod::class, 'domain_payment_method_id');
    }

    public function providerPayments()
    {
        return $this->hasMany(DomainProviderPayment::class, 'domain_payment_id');
    }
}
