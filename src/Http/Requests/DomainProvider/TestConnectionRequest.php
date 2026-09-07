<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainProvider;

use Illuminate\Foundation\Http\FormRequest;
use Innoboxrr\DomainManager\Models\DomainProvider;

class TestConnectionRequest extends FormRequest
{
    public function authorize()
    {
        $domainProvider = DomainProvider::findOrFail($this->domain_provider_id);

        return $this->user()->can('update', $domainProvider);
    }

    public function rules()
    {
        return [
            'domain_provider_id' => ['required', 'integer', 'exists:domain_providers,id'],
        ];
    }

    public function handle()
    {
        $domainProvider = DomainProvider::findOrFail($this->domain_provider_id);
        $result = $domainProvider->testConnection();

        return response()->json($result);
    }
}
