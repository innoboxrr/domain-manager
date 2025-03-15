<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainProviderPayment;

use Innoboxrr\DomainManager\Models\DomainProviderPayment;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainProviderPaymentResource;
use Innoboxrr\DomainManager\Http\Events\DomainProviderPayment\Events\RestoreEvent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RestoreRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        //
    }

    public function authorize()
    {
        
        $domainProviderPayment = DomainProviderPayment::withTrashed()->findOrFail($this->domain_provider_payment_id);
        
        return $this->user()->can('restore', $domainProviderPayment);

    }

    public function rules()
    {
        return [
            'domain_provider_payment_id' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            //
        ];
    }

    public function attributes()
    {
        return [
            //
        ];
    }

    protected function passedValidation()
    {
        //
    }

    public function handle()
    {

        $domainProviderPayment = DomainProviderPayment::withTrashed()->findOrFail($this->domain_provider_payment_id);

        $domainProviderPayment->restoreModel();

        $response = new DomainProviderPaymentResource($domainProviderPayment);

        event(new RestoreEvent($domainProviderPayment, $this->all(), $response));

        return $response;

    }
    
}
