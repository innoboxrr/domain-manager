<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainContact;

use Innoboxrr\DomainManager\Models\DomainContact;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainContactResource;
use Innoboxrr\DomainManager\Http\Events\DomainContact\Events\RestoreEvent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RestoreRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        //
    }

    public function authorize()
    {
        
        $domainContact = DomainContact::withTrashed()->findOrFail($this->domain_contact_id);
        
        return $this->user()->can('restore', $domainContact);

    }

    public function rules()
    {
        return [
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

        $domainContact = DomainContact::withTrashed()->findOrFail($this->domain_contact_id);

        $domainContact->restoreModel();

        $response = new DomainContactResource($domainContact);

        event(new RestoreEvent($domainContact, $this->all(), $response));

        return $response;

    }
    
}
