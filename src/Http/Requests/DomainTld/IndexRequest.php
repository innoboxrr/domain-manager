<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainTld;

use Innoboxrr\DomainManager\Models\DomainTld;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainTldResource;
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
        return $this->user()->can('index', DomainTld::class);
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

        $query = $builder->get(DomainTld::class, $this->all());

        return DomainTldResource::collection($query);

    }
}
