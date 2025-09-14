<?php

namespace Innoboxrr\DomainManager\Http\Requests\Domain;

use Illuminate\Foundation\Http\FormRequest;
use Innoboxrr\DomainManager\Models\Domain;

class RegistarUpsertRecordsRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user() !== null;
    }

    public function rules()
    {
        return [
            'domain_id' => 'required|numeric|exists:domains,id',
            'records' => 'required|array|min:1',
            'records.*.type' => 'required|string',
            'records.*.name' => 'required|string',
            'records.*.ttl' => 'nullable|integer|min:60',
            'records.*.value' => 'nullable',
            'records.*.alias' => 'nullable|array',
        ];
    }

    public function handle()
    {
        $domain = Domain::findOrFail($this->domain_id);

        $ok = $domain->upsertRecords($this->records);

        return response()->json(['ok' => $ok]);
    }
}