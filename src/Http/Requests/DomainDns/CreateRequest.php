<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainDns;

use Innoboxrr\DomainManager\Models\DomainDns;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainDnsResource;
use Innoboxrr\DomainManager\Http\Events\DomainDns\Events\CreateEvent;
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

        return $this->user()->can('create', DomainDns::class);

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

        $domainDns = (new DomainDns)->createModel($this);

        $response = new DomainDnsResource($domainDns);

        event(new CreateEvent($domainDns, $this->all(), $response));

        return $response;

    }
    
}
