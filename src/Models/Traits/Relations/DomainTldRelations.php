<?php

namespace Innoboxrr\DomainManager\Models\Traits\Relations;

use Innoboxrr\DomainManager\Models\Domain;
use Innoboxrr\DomainManager\Models\DomainProvider;

trait DomainTldRelations
{
    public function domains()
    {
        return $this->hasMany(Domain::class, 'domain_tld_id');
    }

    public function providers()
    {
        return $this->belongsToMany(DomainProvider::class, 'domain_provider_tld', 'domain_tld_id', 'domain_provider_id')
            ->withPivot(['price', 'rules'])
            ->withTimestamps();
    }
}
