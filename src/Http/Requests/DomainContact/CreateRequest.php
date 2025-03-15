<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainContact;

use Innoboxrr\DomainManager\Models\DomainContact;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainContactResource;
use Innoboxrr\DomainManager\Http\Events\DomainContact\Events\CreateEvent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        //
    }

    public function authorize()
    {

        return $this->user()->can('create', DomainContact::class);

    }

    public function rules()
    {
        return [
            //
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

        $domainContact = (new DomainContact)->createModel($this);

        $response = new DomainContactResource($domainContact);

        event(new CreateEvent($domainContact, $this->all(), $response));

        return $response;

    }
    
}
