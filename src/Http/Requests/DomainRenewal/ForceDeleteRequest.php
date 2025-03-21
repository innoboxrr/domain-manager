<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainRenewal;

use Innoboxrr\DomainManager\Models\DomainRenewal;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainRenewalResource;
use Innoboxrr\DomainManager\Http\Events\DomainRenewal\Events\ForceDeleteEvent;
use Illuminate\Foundation\Http\FormRequest;

class ForceDeleteRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        //
    }

    public function authorize()
    {

        $domainRenewal = DomainRenewal::withTrashed()->findOrFail($this->domain_renewal_id);
        
        return $this->user()->can('forceDelete', $domainRenewal);

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

        $domainRenewal->forceDeleteModel();

        $response = new DomainRenewalResource($domainRenewal);

        event(new ForceDeleteEvent($domainRenewal, $this->all(), $response));

        return $response;

    }
    
}
