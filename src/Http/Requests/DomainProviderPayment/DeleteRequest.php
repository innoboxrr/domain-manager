<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainProviderPayment;

use Innoboxrr\DomainManager\Models\DomainProviderPayment;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainProviderPaymentResource;
use Innoboxrr\DomainManager\Http\Events\DomainProviderPayment\Events\DeleteEvent;
use Illuminate\Foundation\Http\FormRequest;

class DeleteRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        //
    }

    public function authorize()
    {
        
        $domainProviderPayment = DomainProviderPayment::findOrFail($this->domain_provider_payment_id);

        return $this->user()->can('delete', $domainProviderPayment);

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

        $domainProviderPayment = DomainProviderPayment::findOrFail($this->domain_provider_payment_id);

        $domainProviderPayment->deleteModel();

        $response = new DomainProviderPaymentResource($domainProviderPayment);

        event(new DeleteEvent($domainProviderPayment, $this->all(), $response));

        return $response;

    }
    
}
