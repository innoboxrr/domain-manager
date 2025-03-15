<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainSubscription;

use Innoboxrr\DomainManager\Models\DomainSubscription;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainSubscriptionResource;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShowRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        //
    }

    public function authorize()
    {

        $domainSubscription = DomainSubscription::findOrFail($this->domain_subscription_id);

        return $this->user()->can('view', $domainSubscription);

    }

    public function rules()
    {
        return [
            'load_relations' => [
                'nullable',
                'array',
                Rule::in(DomainSubscription::$loadable_relations)
            ],
            'load_counts' => [
                'nullable',
                'array',
                Rule::in(DomainSubscription::$loadable_counts)
            ],
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

        $domainSubscription = DomainSubscription::where('id', $this->domain_subscription_id)
            ->with($this->load_relations ?? [])
            ->withCount($this->load_counts ?? [])
            ->firstOrFail();

        return new DomainSubscriptionResource($domainSubscription);

    }
    
}
