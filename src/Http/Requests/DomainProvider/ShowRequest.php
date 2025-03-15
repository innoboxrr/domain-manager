<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainProvider;

use Innoboxrr\DomainManager\Models\DomainProvider;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainProviderResource;
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

        $domainProvider = DomainProvider::findOrFail($this->domain_provider_id);

        return $this->user()->can('view', $domainProvider);

    }

    public function rules()
    {
        return [
            'load_relations' => [
                'nullable',
                'array',
                Rule::in(DomainProvider::$loadable_relations)
            ],
            'load_counts' => [
                'nullable',
                'array',
                Rule::in(DomainProvider::$loadable_counts)
            ],
            'domain_provider_id' => 'required|numeric'
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

        $domainProvider = DomainProvider::where('id', $this->domain_provider_id)
            ->with($this->load_relations ?? [])
            ->withCount($this->load_counts ?? [])
            ->firstOrFail();

        return new DomainProviderResource($domainProvider);

    }
    
}
