<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainPayment;

use Innoboxrr\DomainManager\Models\DomainPayment;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainPaymentResource;
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
        return $this->user()->can('index', DomainPayment::class);
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

        $query = $builder->get(DomainPayment::class, $this->all());

        return DomainPaymentResource::collection($query);

    }
}
