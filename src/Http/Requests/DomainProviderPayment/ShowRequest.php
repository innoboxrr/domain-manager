<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainProviderPayment;

use Innoboxrr\DomainManager\Models\DomainProviderPayment;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainProviderPaymentResource;
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

        $domainProviderPayment = DomainProviderPayment::findOrFail($this->domain_provider_payment_id);

        return $this->user()->can('view', $domainProviderPayment);

    }

    public function rules()
    {
        return [
            'load_relations' => [
                'nullable',
                'array',
                Rule::in(DomainProviderPayment::$loadable_relations)
            ],
            'load_counts' => [
                'nullable',
                'array',
                Rule::in(DomainProviderPayment::$loadable_counts)
            ],
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

        $domainProviderPayment = DomainProviderPayment::where('id', $this->domain_provider_payment_id)
            ->with($this->load_relations ?? [])
            ->withCount($this->load_counts ?? [])
            ->firstOrFail();

        return new DomainProviderPaymentResource($domainProviderPayment);

    }
    
}
