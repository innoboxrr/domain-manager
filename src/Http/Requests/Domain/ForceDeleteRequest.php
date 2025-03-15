<?php

namespace Innoboxrr\DomainManager\Http\Requests\Domain;

use Innoboxrr\DomainManager\Models\Domain;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainResource;
use Innoboxrr\DomainManager\Http\Events\Domain\Events\ForceDeleteEvent;
use Illuminate\Foundation\Http\FormRequest;

class ForceDeleteRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        //
    }

    public function authorize()
    {

        $domain = Domain::withTrashed()->findOrFail($this->domain_id);
        
        return $this->user()->can('forceDelete', $domain);

    }

    public function rules()
    {
        return [
            'domain_id' => 'required|numeric'
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

        $domain = Domain::withTrashed()->findOrFail($this->domain_id);

        $domain->forceDeleteModel();

        $response = new DomainResource($domain);

        event(new ForceDeleteEvent($domain, $this->all(), $response));

        return $response;

    }
    
}
