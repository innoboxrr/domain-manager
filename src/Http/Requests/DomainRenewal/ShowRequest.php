<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainRenewal;

use Innoboxrr\DomainManager\Models\DomainRenewal;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainRenewalResource;
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

        $domainRenewal = DomainRenewal::findOrFail($this->domain_renewal_id);

        return $this->user()->can('view', $domainRenewal);

    }

    public function rules()
    {
        return [
            'load_relations' => [
                'nullable',
                'array',
                Rule::in(DomainRenewal::$loadable_relations)
            ],
            'load_counts' => [
                'nullable',
                'array',
                Rule::in(DomainRenewal::$loadable_counts)
            ],
            'domain_renewal_id' => 'required|numeric'
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

        $domainRenewal = DomainRenewal::where('id', $this->domain_renewal_id)
            ->with($this->load_relations ?? [])
            ->withCount($this->load_counts ?? [])
            ->firstOrFail();

        return new DomainRenewalResource($domainRenewal);

    }
    
}
