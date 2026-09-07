<?php

namespace Innoboxrr\DomainManager\Http\Requests\Domain;

use Illuminate\Foundation\Http\FormRequest;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainResource;
use Innoboxrr\DomainManager\Models\Domain;

class RegistrarRenewRequest extends FormRequest
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
            'auto_renew' => 'nullable|boolean',
            'domain_payment_method_id' => 'required|numeric|exists:domain_payment_methods,id',
            'transaction_id' => 'required|string|max:191',
            'amount' => 'required|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'provider_amount' => 'nullable|numeric|min:0',
        ];
    }

    public function handle()
    {
        $domain = Domain::findOrFail($this->domain_id);

        $years = (int) $this->input('years', 1);

        $domain = $domain->renewRegistration($years, [
            'auto_renew' => (bool) $this->input('auto_renew', true),
            'user' => $this->user(),
            'domain_payment_method_id' => (int) $this->input('domain_payment_method_id'),
            'transaction_id' => $this->transaction_id,
            'amount' => (float) $this->input('amount'),
            'tax' => (float) $this->input('tax', 0),
            'provider_amount' => (float) $this->input('provider_amount', $this->input('amount', 0)),
        ]);

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
