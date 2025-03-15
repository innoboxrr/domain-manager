<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainTld;

use Innoboxrr\DomainManager\Models\DomainTld;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainTldResource;
use Innoboxrr\DomainManager\Http\Events\DomainTld\Events\ForceDeleteEvent;
use Illuminate\Foundation\Http\FormRequest;

class ForceDeleteRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        //
    }

    public function authorize()
    {

        $domainTld = DomainTld::withTrashed()->findOrFail($this->domain_tld_id);
        
        return $this->user()->can('forceDelete', $domainTld);

    }

    public function rules()
    {
        return [
            'domain_tld_id' => 'required|numeric'
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

        $domainTld = DomainTld::withTrashed()->findOrFail($this->domain_tld_id);

        $domainTld->forceDeleteModel();

        $response = new DomainTldResource($domainTld);

        event(new ForceDeleteEvent($domainTld, $this->all(), $response));

        return $response;

    }
    
}
