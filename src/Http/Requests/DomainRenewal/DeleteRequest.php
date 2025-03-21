<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainRenewal;

use Innoboxrr\DomainManager\Models\DomainRenewal;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainRenewalResource;
use Innoboxrr\DomainManager\Http\Events\DomainRenewal\Events\DeleteEvent;
use Illuminate\Foundation\Http\FormRequest;

class DeleteRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        //
    }

    public function authorize()
    {
        
        $domainRenewal = DomainRenewal::findOrFail($this->domain_renewal_id);

        return $this->user()->can('delete', $domainRenewal);

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

        $domainRenewal = DomainRenewal::findOrFail($this->domain_renewal_id);

        $domainRenewal->deleteModel();

        $response = new DomainRenewalResource($domainRenewal);

        event(new DeleteEvent($domainRenewal, $this->all(), $response));

        return $response;

    }
    
}
