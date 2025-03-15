<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainPayment;

use Innoboxrr\DomainManager\Models\DomainPayment;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainPaymentResource;
use Innoboxrr\DomainManager\Http\Events\DomainPayment\Events\DeleteEvent;
use Illuminate\Foundation\Http\FormRequest;

class DeleteRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        //
    }

    public function authorize()
    {
        
        $domainPayment = DomainPayment::findOrFail($this->domain_payment_id);

        return $this->user()->can('delete', $domainPayment);

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

        $domainPayment = DomainPayment::findOrFail($this->domain_payment_id);

        $domainPayment->deleteModel();

        $response = new DomainPaymentResource($domainPayment);

        event(new DeleteEvent($domainPayment, $this->all(), $response));

        return $response;

    }
    
}
