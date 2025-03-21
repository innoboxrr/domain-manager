<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainPaymentMethod;

use Innoboxrr\DomainManager\Models\DomainPaymentMethod;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainPaymentMethodResource;
use Innoboxrr\DomainManager\Http\Events\DomainPaymentMethod\Events\RestoreEvent;
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
        
        $domainPaymentMethod = DomainPaymentMethod::withTrashed()->findOrFail($this->domain_payment_method_id);
        
        return $this->user()->can('restore', $domainPaymentMethod);

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

        $domainPaymentMethod = DomainPaymentMethod::withTrashed()->findOrFail($this->domain_payment_method_id);

        $domainPaymentMethod->restoreModel();

        $response = new DomainPaymentMethodResource($domainPaymentMethod);

        event(new RestoreEvent($domainPaymentMethod, $this->all(), $response));

        return $response;

    }
    
}
