<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainPaymentMethod;

use Innoboxrr\DomainManager\Models\DomainPaymentMethod;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainPaymentMethodResource;
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

        $domainPaymentMethod = DomainPaymentMethod::findOrFail($this->domain_payment_method_id);

        return $this->user()->can('view', $domainPaymentMethod);

    }

    public function rules()
    {
        return [
            'load_relations' => [
                'nullable',
                'array',
                Rule::in(DomainPaymentMethod::$loadable_relations)
            ],
            'load_counts' => [
                'nullable',
                'array',
                Rule::in(DomainPaymentMethod::$loadable_counts)
            ],
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

        $domainPaymentMethod = DomainPaymentMethod::where('id', $this->domain_payment_method_id)
            ->with($this->load_relations ?? [])
            ->withCount($this->load_counts ?? [])
            ->firstOrFail();

        return new DomainPaymentMethodResource($domainPaymentMethod);

    }
    
}
