<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainProvider;

use Innoboxrr\DomainManager\Models\DomainProvider;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainProviderResource;
use Innoboxrr\DomainManager\Http\Events\DomainProvider\Events\RestoreEvent;
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
        
        $domainProvider = DomainProvider::withTrashed()->findOrFail($this->domain_provider_id);
        
        return $this->user()->can('restore', $domainProvider);

    }

    public function rules()
    {
        return [
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

        $domainProvider = DomainProvider::withTrashed()->findOrFail($this->domain_provider_id);

        $domainProvider->restoreModel();

        $response = new DomainProviderResource($domainProvider);

        event(new RestoreEvent($domainProvider, $this->all(), $response));

        return $response;

    }
    
}
