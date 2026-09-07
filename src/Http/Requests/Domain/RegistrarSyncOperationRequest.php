<?php

namespace Innoboxrr\DomainManager\Http\Requests\Domain;

use Illuminate\Foundation\Http\FormRequest;
use Innoboxrr\DomainManager\Http\Resources\Models\DomainResource;
use Innoboxrr\DomainManager\Models\Domain;

class RegistrarSyncOperationRequest extends FormRequest
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

        $domain = $domain->syncOperation();

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
