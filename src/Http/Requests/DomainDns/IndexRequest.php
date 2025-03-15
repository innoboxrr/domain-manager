<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainDns;

use Innoboxrr\DomainManager\Models\DomainDns;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainDnsResource;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Innoboxrr\SearchSurge\Search\Builder;

class IndexRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        //
    }

    public function authorize()
    {
        return $this->user()->can('index', DomainDns::class);
    }

    public function rules()
    {
        return [
            //
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

        $builder = new Builder();

        $query = $builder->get(DomainDns::class, $this->all());

        return DomainDnsResource::collection($query);

    }
}
