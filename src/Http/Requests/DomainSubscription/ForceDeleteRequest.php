<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainSubscription;

use Innoboxrr\DomainManager\Models\DomainSubscription;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainSubscriptionResource;
use Innoboxrr\DomainManager\Http\Events\DomainSubscription\Events\ForceDeleteEvent;
use Illuminate\Foundation\Http\FormRequest;

class ForceDeleteRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        //
    }

    public function authorize()
    {

        $domainSubscription = DomainSubscription::withTrashed()->findOrFail($this->domain_subscription_id);
        
        return $this->user()->can('forceDelete', $domainSubscription);

    }

    public function rules()
    {
        return [
            'domain_subscription_id' => 'required|numeric'
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

        $domainSubscription = DomainSubscription::withTrashed()->findOrFail($this->domain_subscription_id);

        $domainSubscription->forceDeleteModel();

        $response = new DomainSubscriptionResource($domainSubscription);

        event(new ForceDeleteEvent($domainSubscription, $this->all(), $response));

        return $response;

    }
    
}
