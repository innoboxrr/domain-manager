<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainContact;

use Innoboxrr\DomainManager\Models\DomainContact;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainContactResource;
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

        $domainContact = DomainContact::findOrFail($this->domain_contact_id);

        return $this->user()->can('view', $domainContact);

    }

    public function rules()
    {
        return [
            'load_relations' => [
                'nullable',
                'array',
                Rule::in(DomainContact::$loadable_relations)
            ],
            'load_counts' => [
                'nullable',
                'array',
                Rule::in(DomainContact::$loadable_counts)
            ],
            'domain_contact_id' => 'required|numeric'
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

        $domainContact = DomainContact::where('id', $this->domain_contact_id)
            ->with($this->load_relations ?? [])
            ->withCount($this->load_counts ?? [])
            ->firstOrFail();

        return new DomainContactResource($domainContact);

    }
    
}
