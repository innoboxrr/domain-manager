<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainDns;

use Innoboxrr\DomainManager\Models\DomainDns;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainDnsResource;
use Innoboxrr\DomainManager\Http\Events\DomainDns\Events\ForceDeleteEvent;
use Illuminate\Foundation\Http\FormRequest;

class ForceDeleteRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        //
    }

    public function authorize()
    {

        $domainDns = DomainDns::withTrashed()->findOrFail($this->domain_dns_id);
        
        return $this->user()->can('forceDelete', $domainDns);

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

        $domainDns->forceDeleteModel();

        $response = new DomainDnsResource($domainDns);

        event(new ForceDeleteEvent($domainDns, $this->all(), $response));

        return $response;

    }
    
}
