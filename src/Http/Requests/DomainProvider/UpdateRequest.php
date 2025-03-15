<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainProvider;

use Innoboxrr\DomainManager\Models\DomainProvider;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainProviderResource;
use Innoboxrr\DomainManager\Http\Events\DomainProvider\Events\UpdateEvent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        //
    }

    public function authorize()
    {
        
        $domainProvider = DomainProvider::findOrFail($this->domain_provider_id);

        return $this->user()->can('update', $domainProvider);

    }

    public function rules()
    {
        return [
            //
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

        $domainProvider = $domainProvider->updateModel($this);

        $response = new DomainProviderResource($domainProvider);

        event(new UpdateEvent($domainProvider, $this->all(), $response));

        return $response;

    }

}
