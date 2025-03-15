<?php

namespace Innoboxrr\DomainManager\Models\Traits\Storage;

// use Innoboxrr\DomainManager\Models\DomainContactMeta;

trait DomainContactStorage
{

    public function createModel($request)
    {

        $domainContact = $this->create($request->only($this->creatable));

        return $domainContact;

    }

    public function updateModel($request)
    {
     
        $this->update($request->only($this->updatable));

        return $this;

    }

    /*
    public function updateModelMetas($request)
    {

        $this->update_metas($request, DomainContactMeta::class, 'domain_contact_id')->updatePayload();

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