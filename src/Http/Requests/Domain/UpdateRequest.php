<?php

namespace Innoboxrr\DomainManager\Http\Requests\Domain;

use Innoboxrr\DomainManager\Models\Domain;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainResource;
use Innoboxrr\DomainManager\Http\Events\Domain\Events\UpdateEvent;
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
        
        $domain = Domain::findOrFail($this->domain_id);

        return $this->user()->can('update', $domain);

    }

    public function rules()
    {
        return [
            //
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

        $domain = $domain->updateModel($this);

        $response = new DomainResource($domain);

        event(new UpdateEvent($domain, $this->all(), $response));

        return $response;

    }

}
