<?php

namespace Innoboxrr\DomainManager\Http\Requests\Domain;

use Illuminate\Foundation\Http\FormRequest;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainResource;
use Innoboxrr\DomainManager\Models\Domain;

class RegistrarTransferRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user() !== null;
    }

    public function rules()
    {
        return [
            'domain_id' => 'required|numeric|exists:domains,id',
            'auth_code' => 'required|string|min:6',
            'years' => 'nullable|integer|min:1|max:10',
            'privacy' => 'nullable|boolean',
            'auto_renew' => 'nullable|boolean',
            'contact' => 'nullable|array',
            'domain_payment_method_id' => 'nullable|numeric|exists:domain_payment_methods,id',
            'transaction_id' => 'nullable|string|max:191',
            'amount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'provider_amount' => 'nullable|numeric|min:0',
        ];
    }

    public function handle()
    {
        $domain = Domain::findOrFail($this->domain_id);

        $payload = [
            'auth_code' => $this->auth_code,
            'years' => (int) $this->input('years', 1),
            'privacy' => (bool) $this->input('privacy', true),
            'auto_renew' => (bool) $this->input('auto_renew', true),
            'contact' => $this->input('contact', []),
            'user' => $this->user(),
        ];

        if ($this->filled('domain_payment_method_id')) {
            $payload['domain_payment_method_id'] = (int) $this->input('domain_payment_method_id');
        }

        if ($this->filled('transaction_id')) {
            $payload['transaction_id'] = $this->transaction_id;
        }

        if ($this->filled('amount')) {
            $payload['amount'] = (float) $this->input('amount');
        }

        if ($this->filled('tax')) {
            $payload['tax'] = (float) $this->input('tax');
        }

        if ($this->filled('provider_amount')) {
            $payload['provider_amount'] = (float) $this->input('provider_amount');
        }

        $domain = $domain->initiateTransfer($payload);

        return new DomainResource(
            $domain->load([
                'provider',
                'contacts',
                'dns',
                'subscription',
                'subscription.paymentMethod',
                'subscription.renewals',
                'subscription.renewals.payments',
                'subscription.renewals.payments.paymentMethod',
                'subscription.renewals.payments.providerPayments',
                'subscription.renewals.payments.providerPayments.provider',
            ])
        );
    }
}
