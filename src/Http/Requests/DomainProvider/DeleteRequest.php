<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainProvider;

use Innoboxrr\DomainManager\Models\DomainProvider;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainProviderResource;
use Innoboxrr\DomainManager\Http\Events\DomainProvider\Events\DeleteEvent;
use Illuminate\Foundation\Http\FormRequest;

class DeleteRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        //
    }

    public function authorize()
    {
        
        $domainProvider = DomainProvider::findOrFail($this->domain_provider_id);

        return $this->user()->can('delete', $domainProvider);

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

        $domainProvider = DomainProvider::findOrFail($this->domain_provider_id);

        $domainProvider->deleteModel();

        $response = new DomainProviderResource($domainProvider);

        event(new DeleteEvent($domainProvider, $this->all(), $response));

        return $response;

    }
    
}
