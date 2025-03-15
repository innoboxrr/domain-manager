<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainProvider;

use Innoboxrr\DomainManager\Models\DomainProvider;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainProviderResource;
use Innoboxrr\DomainManager\Http\Events\DomainProvider\Events\CreateEvent;
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

        return $this->user()->can('create', DomainProvider::class);

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

        $domainProvider = (new DomainProvider)->createModel($this);

        $response = new DomainProviderResource($domainProvider);

        event(new CreateEvent($domainProvider, $this->all(), $response));

        return $response;

    }
    
}
