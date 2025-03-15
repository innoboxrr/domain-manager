<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainProvider;

use Innoboxrr\DomainManager\Models\DomainProvider;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainProviderResource;
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
        return $this->user()->can('index', DomainProvider::class);
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

        $query = $builder->get(DomainProvider::class, $this->all());

        return DomainProviderResource::collection($query);

    }
}
