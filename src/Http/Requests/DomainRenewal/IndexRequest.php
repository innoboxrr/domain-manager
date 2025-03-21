<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainRenewal;

use Innoboxrr\DomainManager\Models\DomainRenewal;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainRenewalResource;
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
        return $this->user()->can('index', DomainRenewal::class);
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

        $query = $builder->get(DomainRenewal::class, $this->all());

        return DomainRenewalResource::collection($query);

    }
}
