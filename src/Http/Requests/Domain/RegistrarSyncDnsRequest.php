<?php

namespace Innoboxrr\DomainManager\Http\Requests\Domain;

use Illuminate\Foundation\Http\FormRequest;
use Innoboxrr\DomainManager\Models\Domain;

class RegistrarSyncDnsRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user() !== null;
    }

    public function rules()
    {
        return [
            'domain_id' => 'required|numeric|exists:domains,id',
            'persist' => 'nullable|boolean',
        ];
    }

    public function handle()
    {
        $domain = Domain::findOrFail($this->domain_id);

        $records = $domain->fetchRemoteDns((bool) $this->input('persist', false));

        return response()->json([
            'domain_id' => $domain->id,
            'records' => $records,
        ]);
    }
}
