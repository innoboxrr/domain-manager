<?php

namespace Innoboxrr\DomainManager\Models\Traits\Storage;

// use Innoboxrr\DomainManager\Models\DomainProviderMeta;

trait DomainProviderStorage
{

    public function createModel($request)
    {

        $domainProvider = $this->create($request->only($this->creatable));

        return $domainProvider;

    }

    public function updateModel($request)
    {
     
        $this->update($request->only($this->updatable));

        return $this;

    }

    /*
    public function updateModelMetas($request)
    {

        $this->update_metas($request, DomainProviderMeta::class, 'domain_provider_id')->updatePayload();

        return $this;

    }
    */

    public function deleteModel()
    {

        $this->delete();

    }

    public function restoreModel()
    {

        $this->restore();

    }

    public function forceDeleteModel()
    {

        abort(403);

        $this->forceDelete();
        
    }

}