<?php

namespace Innoboxrr\DomainManager\Models\Traits\Relations;

use Innoboxrr\DomainManager\Models\DomainProvider;
use Innoboxrr\DomainManager\Models\DomainPayment;

trait DomainProviderPaymentRelations
{
    public function provider()
    {
        return $this->belongsTo(DomainProvider::class, 'domain_provider_id');
    }

    public function domainPayment()
    {
        return $this->belongsTo(DomainPayment::class, 'domain_payment_id');
    }
}
