<?php

namespace Innoboxrr\DomainManager\Http\Requests\Domain;

use Innoboxrr\DomainManager\Models\Domain;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainResource;
use Innoboxrr\DomainManager\Http\Events\Domain\Events\DeleteEvent;
use Illuminate\Foundation\Http\FormRequest;

class DeleteRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        //
    }

    public function authorize()
    {
        
        $domain = Domain::findOrFail($this->domain_id);

        return $this->user()->can('delete', $domain);

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

        $domain = Domain::findOrFail($this->domain_id);

        $domain->deleteModel();

        $response = new DomainResource($domain);

        event(new DeleteEvent($domain, $this->all(), $response));

        return $response;

    }
    
}
