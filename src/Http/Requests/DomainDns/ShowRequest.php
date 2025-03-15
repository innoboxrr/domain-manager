<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainDns;

use Innoboxrr\DomainManager\Models\DomainDns;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainDnsResource;
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

        $domainDns = DomainDns::findOrFail($this->domain_dns_id);

        return $this->user()->can('view', $domainDns);

    }

    public function rules()
    {
        return [
            'load_relations' => [
                'nullable',
                'array',
                Rule::in(DomainDns::$loadable_relations)
            ],
            'load_counts' => [
                'nullable',
                'array',
                Rule::in(DomainDns::$loadable_counts)
            ],
            'domain_dns_id' => 'required|numeric'
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

        $domainDns = DomainDns::where('id', $this->domain_dns_id)
            ->with($this->load_relations ?? [])
            ->withCount($this->load_counts ?? [])
            ->firstOrFail();

        return new DomainDnsResource($domainDns);

    }
    
}
