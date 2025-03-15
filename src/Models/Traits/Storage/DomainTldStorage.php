<?php

namespace Innoboxrr\DomainManager\Models\Traits\Storage;

// use Innoboxrr\DomainManager\Models\DomainTldMeta;

trait DomainTldStorage
{

    public function createModel($request)
    {

        $domainTld = $this->create($request->only($this->creatable));

        return $domainTld;

    }

    public function updateModel($request)
    {
     
        $this->update($request->only($this->updatable));

        return $this;

    }

    /*
    public function updateModelMetas($request)
    {

        $this->update_metas($request, DomainTldMeta::class, 'domain_tld_id')->updatePayload();

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