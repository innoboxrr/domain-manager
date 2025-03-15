<?php

namespace Innoboxrr\DomainManager\Models\Traits\Storage;

// use Innoboxrr\DomainManager\Models\DomainProviderPaymentMeta;

trait DomainProviderPaymentStorage
{

    public function createModel($request)
    {

        $domainProviderPayment = $this->create($request->only($this->creatable));

        return $domainProviderPayment;

    }

    public function updateModel($request)
    {
     
        $this->update($request->only($this->updatable));

        return $this;

    }

    /*
    public function updateModelMetas($request)
    {

        $this->update_metas($request, DomainProviderPaymentMeta::class, 'domain_provider_payment_id')->updatePayload();

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