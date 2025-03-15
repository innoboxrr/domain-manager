<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainTld;

use Innoboxrr\DomainManager\Models\DomainTld;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainTldResource;
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

        $domainTld = DomainTld::findOrFail($this->domain_tld_id);

        return $this->user()->can('view', $domainTld);

    }

    public function rules()
    {
        return [
            'load_relations' => [
                'nullable',
                'array',
                Rule::in(DomainTld::$loadable_relations)
            ],
            'load_counts' => [
                'nullable',
                'array',
                Rule::in(DomainTld::$loadable_counts)
            ],
            'domain_tld_id' => 'required|numeric'
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

        $domainTld = DomainTld::where('id', $this->domain_tld_id)
            ->with($this->load_relations ?? [])
            ->withCount($this->load_counts ?? [])
            ->firstOrFail();

        return new DomainTldResource($domainTld);

    }
    
}
