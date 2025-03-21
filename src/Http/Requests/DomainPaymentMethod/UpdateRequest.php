<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainPaymentMethod;

use Innoboxrr\DomainManager\Models\DomainPaymentMethod;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainPaymentMethodResource;
use Innoboxrr\DomainManager\Http\Events\DomainPaymentMethod\Events\UpdateEvent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        //
    }

    public function authorize()
    {
        
        $domainPaymentMethod = DomainPaymentMethod::findOrFail($this->domain_payment_method_id);

        return $this->user()->can('update', $domainPaymentMethod);

    }

    public function rules()
    {
        return [
            //
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

        $domainPaymentMethod = $domainPaymentMethod->updateModel($this);

        $response = new DomainPaymentMethodResource($domainPaymentMethod);

        event(new UpdateEvent($domainPaymentMethod, $this->all(), $response));

        return $response;

    }

}
