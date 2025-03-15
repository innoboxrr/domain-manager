<?php

namespace Innoboxrr\DomainManager\Http\Requests\Domain;

use Innoboxrr\DomainManager\Models\Domain;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainResource;
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
        return $this->user()->can('index', Domain::class);
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

        $query = $builder->get(Domain::class, $this->all());

        return DomainResource::collection($query);

    }
}
