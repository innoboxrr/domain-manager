<?php

namespace Innoboxrr\DomainManager\Models\Traits\Relations;

use Innoboxrr\DomainManager\Models\Domain;
use Innoboxrr\DomainManager\Models\DomainTld;
use Innoboxrr\DomainManager\Models\DomainProviderPayment;

trait DomainProviderRelations
{
    public function workspace()
    {
        $workspaceClass = config('domain-manager.workspace_class', 'App\\Models\\Workspace');
        // En tu esquema actual workspace_id es opcional; agrega la columna si la usas.
        return $this->belongsTo($workspaceClass, 'workspace_id');
    }

    public function domains()
    {
        return $this->hasMany(Domain::class, 'domain_provider_id');
    }

    public function tlds()
    {
        return $this->belongsToMany(DomainTld::class, 'domain_provider_tld', 'domain_provider_id', 'domain_tld_id')
            ->withPivot(['price', 'rules'])
            ->withTimestamps();
    }

    public function providerPayments()
    {
        return $this->hasMany(DomainProviderPayment::class, 'domain_provider_id');
    }
}
