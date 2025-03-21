<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainPaymentMethod;

use Innoboxrr\DomainManager\Models\DomainPaymentMethod;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainPaymentMethodResource;
use Innoboxrr\DomainManager\Http\Events\DomainPaymentMethod\Events\DeleteEvent;
use Illuminate\Foundation\Http\FormRequest;

class DeleteRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        //
    }

    public function authorize()
    {
        
        $domainPaymentMethod = DomainPaymentMethod::findOrFail($this->domain_payment_method_id);

        return $this->user()->can('delete', $domainPaymentMethod);

    }

    public function rules()
    {
        return [
            'domain_payment_method_id' => 'required|numeric'
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

        $domainPaymentMethod = DomainPaymentMethod::findOrFail($this->domain_payment_method_id);

        $domainPaymentMethod->deleteModel();

        $response = new DomainPaymentMethodResource($domainPaymentMethod);

        event(new DeleteEvent($domainPaymentMethod, $this->all(), $response));

        return $response;

    }
    
}
