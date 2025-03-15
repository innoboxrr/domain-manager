<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainSubscription;

use Innoboxrr\DomainManager\Models\DomainSubscription;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainSubscriptionResource;
use Innoboxrr\DomainManager\Http\Events\DomainSubscription\Events\CreateEvent;
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

        return $this->user()->can('create', DomainSubscription::class);

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

        $domainSubscription = (new DomainSubscription)->createModel($this);

        $response = new DomainSubscriptionResource($domainSubscription);

        event(new CreateEvent($domainSubscription, $this->all(), $response));

        return $response;

    }
    
}
