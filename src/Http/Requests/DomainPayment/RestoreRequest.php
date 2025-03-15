<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainPayment;

use Innoboxrr\DomainManager\Models\DomainPayment;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainPaymentResource;
use Innoboxrr\DomainManager\Http\Events\DomainPayment\Events\RestoreEvent;
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
        
        $domainPayment = DomainPayment::withTrashed()->findOrFail($this->domain_payment_id);
        
        return $this->user()->can('restore', $domainPayment);

    }

    public function rules()
    {
        return [
            'domain_payment_id' => 'required|numeric'
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

        $domainPayment = DomainPayment::withTrashed()->findOrFail($this->domain_payment_id);

        $domainPayment->restoreModel();

        $response = new DomainPaymentResource($domainPayment);

        event(new RestoreEvent($domainPayment, $this->all(), $response));

        return $response;

    }
    
}
