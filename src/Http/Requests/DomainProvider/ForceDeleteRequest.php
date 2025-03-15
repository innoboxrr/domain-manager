<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainProvider;

use Innoboxrr\DomainManager\Models\DomainProvider;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainProviderResource;
use Innoboxrr\DomainManager\Http\Events\DomainProvider\Events\ForceDeleteEvent;
use Illuminate\Foundation\Http\FormRequest;

class ForceDeleteRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        //
    }

    public function authorize()
    {

        $domainProvider = DomainProvider::withTrashed()->findOrFail($this->domain_provider_id);
        
        return $this->user()->can('forceDelete', $domainProvider);

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

        $domainProvider->forceDeleteModel();

        $response = new DomainProviderResource($domainProvider);

        event(new ForceDeleteEvent($domainProvider, $this->all(), $response));

        return $response;

    }
    
}
