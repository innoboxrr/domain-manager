<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainDns;

use Innoboxrr\DomainManager\Models\DomainDns;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainDnsResource;
use Innoboxrr\DomainManager\Http\Events\DomainDns\Events\RestoreEvent;
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
        
        $domainDns = DomainDns::withTrashed()->findOrFail($this->domain_dns_id);
        
        return $this->user()->can('restore', $domainDns);

    }

    public function rules()
    {
        return [
            'domain_dns_id' => 'required|numeric'
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

        $domainDns = DomainDns::withTrashed()->findOrFail($this->domain_dns_id);

        $domainDns->restoreModel();

        $response = new DomainDnsResource($domainDns);

        event(new RestoreEvent($domainDns, $this->all(), $response));

        return $response;

    }
    
}
