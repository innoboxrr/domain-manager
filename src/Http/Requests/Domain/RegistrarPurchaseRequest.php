<?php

namespace Innoboxrr\DomainManager\Http\Requests\Domain;

use Illuminate\Foundation\Http\FormRequest;
use Innoboxrr\DomainManager\Models\Domain;

class RegistrarPurchaseRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user() !== null;
    }

    public function rules()
    {
        return [
            'domain_id' => 'required|numeric|exists:domains,id',
            'years' => 'nullable|integer|min:1|max:10',
            'privacy' => 'nullable|boolean',
            'auto_renew' => 'nullable|boolean',
            'contact' => 'required|array',
        ];
    }

    public function handle()
    {
        $domain = Domain::findOrFail($this->domain_id);

        $res = $domain->purchase([
            'years' => (int)($this->years ?? 1),
            'privacy' => (bool)($this->privacy ?? true),
            'auto_renew' => (bool)($this->auto_renew ?? true),
            'contact' => $this->contact,
        ]);

        return response()->json($res);
    }
}
