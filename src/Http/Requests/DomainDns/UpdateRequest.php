<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainDns;

use Innoboxrr\DomainManager\Models\DomainDns;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainDnsResource;
use Innoboxrr\DomainManager\Http\Events\DomainDns\Events\UpdateEvent;
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
        
        $domainDns = DomainDns::findOrFail($this->domain_dns_id);

        return $this->user()->can('update', $domainDns);

    }

    public function rules()
    {
        return [
            //
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

        $domainDns = DomainDns::findOrFail($this->domain_dns_id);

        $domainDns = $domainDns->updateModel($this);

        $response = new DomainDnsResource($domainDns);

        event(new UpdateEvent($domainDns, $this->all(), $response));

        return $response;

    }

}
