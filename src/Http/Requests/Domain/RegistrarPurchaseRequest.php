<?php

namespace Innoboxrr\DomainManager\Http\Requests\Domain;

use Illuminate\Foundation\Http\FormRequest;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainResource;
use Innoboxrr\DomainManager\Models\Domain;
use Innoboxrr\DomainManager\Models\DomainProvider;
use Innoboxrr\DomainManager\Models\DomainTld;

class RegistrarPurchaseRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user() !== null;
    }

    public function rules()
    {
        return [
            'domain_id' => 'nullable|numeric|exists:domains,id',
            'domain' => [
                'required_without:domain_id',
                'string',
                'regex:/^(?!-)[A-Za-z0-9-]{1,63}(?<!-)(\.[A-Za-z]{2,})+$/',
            ],
            'provider_id' => 'required_without:domain_id|numeric|exists:domain_providers,id',
            'years' => 'nullable|integer|min:1|max:10',
            'privacy' => 'nullable|boolean',
            'auto_renew' => 'nullable|boolean',
            'contact' => 'required|array',
            'domain_payment_method_id' => 'required|numeric|exists:domain_payment_methods,id',
            'transaction_id' => 'required|string|max:191',
            'amount' => 'required|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'provider_amount' => 'nullable|numeric|min:0',
        ];
    }

    public function handle()
    {
        $domain = $this->resolveDomain();

        $domain = $domain->purchase([
            'years' => (int)($this->input('years', 1)),
            'privacy' => (bool)($this->input('privacy', true)),
            'auto_renew' => (bool)($this->input('auto_renew', true)),
            'contact' => $this->contact,
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

    protected function resolveDomain(): Domain
    {
        if ($this->domain_id) {
            return Domain::findOrFail($this->domain_id);
        }

        $fqdn = strtolower($this->domain);
        $provider = DomainProvider::findOrFail($this->provider_id);

        $tldPart = $this->input('tld');
        if (!$tldPart) {
            $pos = strpos($fqdn, '.');
            $tldPart = $pos !== false ? substr($fqdn, $pos) : ''; // incluye el punto
        }

        if ($tldPart === '' || $tldPart === false) {
            throw new \InvalidArgumentException('No se pudo determinar el TLD del dominio.');
        }

        $tldModel = DomainTld::firstOrCreate([
            'name' => $tldPart,
        ]);

        return Domain::firstOrCreate([
            'name' => $fqdn,
        ], [
            'status' => 'draft',
            'domain_provider_id' => $provider->id,
            'domain_tld_id' => $tldModel->id,
            'privacy' => (bool)($this->input('privacy', true)),
            'auto_renew' => (bool)($this->input('auto_renew', true)),
        ]);
    }
}
