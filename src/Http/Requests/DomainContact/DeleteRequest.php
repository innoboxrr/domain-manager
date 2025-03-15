<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainContact;

use Innoboxrr\DomainManager\Models\DomainContact;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainContactResource;
use Innoboxrr\DomainManager\Http\Events\DomainContact\Events\DeleteEvent;
use Illuminate\Foundation\Http\FormRequest;

class DeleteRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        //
    }

    public function authorize()
    {
        
        $domainContact = DomainContact::findOrFail($this->domain_contact_id);

        return $this->user()->can('delete', $domainContact);

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

        $domainContact = DomainContact::findOrFail($this->domain_contact_id);

        $domainContact->deleteModel();

        $response = new DomainContactResource($domainContact);

        event(new DeleteEvent($domainContact, $this->all(), $response));

        return $response;

    }
    
}
