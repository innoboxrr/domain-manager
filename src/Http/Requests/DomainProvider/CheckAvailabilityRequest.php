<?php

namespace Innoboxrr\DomainManager\Http\Requests\DomainProvider;

use Illuminate\Foundation\Http\FormRequest;
use Innoboxrr\DomainManager\Models\DomainProvider;

class CheckAvailabilityRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user() !== null;
    }

    public function rules()
    {
        return [
            'provider_id' => 'required|numeric|exists:domain_providers,id',
            'fqdn' => [
                'required','string',
                'regex:/^(?!-)[A-Za-z0-9-]{1,63}(?<!-)(\.[A-Za-z]{2,})+$/'
            ],
        ];
    }

    public function handle()
    {
        $provider = DomainProvider::findOrFail($this->provider_id);

        $res = $provider->checkAvailability(strtolower($this->fqdn));

        return response()->json($res);
    }
}
