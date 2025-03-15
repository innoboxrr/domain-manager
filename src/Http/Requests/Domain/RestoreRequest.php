<?php

namespace Innoboxrr\DomainManager\Http\Requests\Domain;

use Innoboxrr\DomainManager\Models\Domain;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainResource;
use Innoboxrr\DomainManager\Http\Events\Domain\Events\RestoreEvent;
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
        
        $domain = Domain::withTrashed()->findOrFail($this->domain_id);
        
        return $this->user()->can('restore', $domain);

    }

    public function rules()
    {
        return [
            'domain_id' => 'required|numeric'
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

        $domain = Domain::withTrashed()->findOrFail($this->domain_id);

        $domain->restoreModel();

        $response = new DomainResource($domain);

        event(new RestoreEvent($domain, $this->all(), $response));

        return $response;

    }
    
}
