<?php

namespace Innoboxrr\DomainManager\Http\Requests\Domain;

use Illuminate\Foundation\Http\FormRequest;
use Innoboxrr\DomainManager\Models\Domain;

class RegistarSyncOperationRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user() !== null;
    }

    public function rules()
    {
        return [
            'domain_id' => 'required|numeric|exists:domains,id',
        ];
    }

    public function handle()
    {
        $domain = Domain::findOrFail($this->domain_id);

        $res = $domain->syncOperation();

        return response()->json($res);
    }
}
