<?php

namespace Innoboxrr\DomainManager\Models\Traits\Storage;

// use Innoboxrr\DomainManager\Models\DomainMeta;

trait DomainStorage
{

    public function createModel($request)
    {

        $domain = $this->create($request->only($this->creatable));

        return $domain;

    }

    public function updateModel($request)
    {
     
        $this->update($request->only($this->updatable));

        return $this;

    }

    /*
    public function updateModelMetas($request)
    {

        $this->update_metas($request, DomainMeta::class, 'domain_id')->updatePayload();

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