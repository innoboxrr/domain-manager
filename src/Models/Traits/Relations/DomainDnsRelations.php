<?php

namespace Innoboxrr\DomainManager\Models\Traits\Relations;

use Innoboxrr\DomainManager\Models\Domain;

trait DomainDnsRelations
{
    public function domain()
    {
        return $this->belongsTo(Domain::class, 'domain_id');
    }
}
