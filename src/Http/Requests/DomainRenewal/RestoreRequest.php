<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainRenewal;

use Innoboxrr\DomainManager\Models\DomainRenewal;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainRenewalResource;
use Innoboxrr\DomainManager\Http\Events\DomainRenewal\Events\RestoreEvent;
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
        
        $domainRenewal = DomainRenewal::withTrashed()->findOrFail($this->domain_renewal_id);
        
        return $this->user()->can('restore', $domainRenewal);

    }

    public function rules()
    {
        return [
            'domain_renewal_id' => 'required|numeric'
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

        $domainRenewal = DomainRenewal::withTrashed()->findOrFail($this->domain_renewal_id);

        $domainRenewal->restoreModel();

        $response = new DomainRenewalResource($domainRenewal);

        event(new RestoreEvent($domainRenewal, $this->all(), $response));

        return $response;

    }
    
}
