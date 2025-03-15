<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainPayment;

use Innoboxrr\DomainManager\Models\DomainPayment;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainPaymentResource;
use Innoboxrr\DomainManager\Http\Events\DomainPayment\Events\CreateEvent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        //
    }

    public function authorize()
    {

        return $this->user()->can('create', DomainPayment::class);

    }

    public function rules()
    {
        return [
            //
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

        $domainPayment = (new DomainPayment)->createModel($this);

        $response = new DomainPaymentResource($domainPayment);

        event(new CreateEvent($domainPayment, $this->all(), $response));

        return $response;

    }
    
}
