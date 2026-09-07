<?php

namespace Innoboxrr\DomainManager\Models\Traits\Operations;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Innoboxrr\DomainManager\Models\DomainPayment;
use Innoboxrr\DomainManager\Models\DomainPaymentMethod;
use Innoboxrr\DomainManager\Models\DomainSubscription;
use InvalidArgumentException;

trait DomainOperations
{
    protected function getRegistrarDriverFromProvider()
    {
        $provider = $this->provider;

        if (!$provider) {
            throw new InvalidArgumentException('El dominio no tiene un proveedor asociado.');
        }

        return $provider->getRegistrarDriver();
    }

    public function purchase(array $args = []): self
    {
        return DB::transaction(function () use ($args) {
            $years = max(1, (int) ($args['years'] ?? 1));

            $this->status = 'purchasing';
            $this->provider_status = 'SUBMITTED';
            $this->privacy = array_key_exists('privacy', $args) ? (bool) $args['privacy'] : ($this->privacy ?? true);
            $this->auto_renew = array_key_exists('auto_renew', $args) ? (bool) $args['auto_renew'] : ($this->auto_renew ?? true);
            $this->save();

            $driver = $this->getRegistrarDriverFromProvider();

            $operation = $driver->registerDomain([
                'domain' => $this->name,
                'years' => $years,
                'privacy' => $this->privacy,
                'auto_renew' => $this->auto_renew,
                'contact' => $args['contact'] ?? [],
            ]);

            $this->applyOperationResult($operation);

            $this->expires_at = $this->determineExpiration($operation['expires_at'] ?? null, $years);

            if (!empty($operation['nameservers'])) {
                $this->nameservers = $operation['nameservers'];
            }

            if (!empty($args['contact']) && is_array($args['contact'])) {
                $this->storeContact($args['contact']);
            }

            $subscription = $this->ensureSubscription($args + ['years' => $years, 'expires_at' => $this->expires_at]);

            $this->recordFinancialOperation($subscription, $args, $operation, 'initial_purchase');

            $this->save();

            return $this->fresh();
        });
    }

    public function syncOperation(): self
    {
        return DB::transaction(function () {
            $driver = $this->getRegistrarDriverFromProvider();

            $operation = $driver->fetchOperation($this->provider_ref ?? '');
            $this->applyOperationResult($operation);

            if ($this->provider_status === 'SUCCESS') {
                $state = $this->syncRegistrarState();
                if (!empty($state['expires_at'])) {
                    $this->expires_at = $this->parseDateValue($state['expires_at']);
                }
                if (!empty($state['nameservers'])) {
                    $this->nameservers = $state['nameservers'];
                }
                $this->ensureSubscription(['expires_at' => $this->expires_at, 'auto_renew' => $this->auto_renew]);
            }

            $this->save();

            return $this->fresh();
        });
    }

    public function upsertRecords(array $records): bool
    {
        return DB::transaction(function () use ($records) {
            $driver = $this->getRegistrarDriverFromProvider();

            $zone = $driver->ensureHostedZone($this->name);
            $driver->upsertRecords($zone['zone_id'] ?? $this->name, $records);

            $normalized = $this->normalizeDnsRecords($records);
            $this->replaceLocalDns($normalized);

            return true;
        });
    }

    public function fetchRemoteDns(bool $persist = false): array
    {
        $driver = $this->getRegistrarDriverFromProvider();

        if (!method_exists($driver, 'listRecords')) {
            throw new \BadMethodCallException('El driver del registrador no soporta listar registros DNS.');
        }

        $records = $driver->listRecords($this->name);
        $normalized = $this->normalizeDnsRecords($records);

        if ($persist) {
            DB::transaction(fn () => $this->replaceLocalDns($normalized));
        }

        return $normalized;
    }

    public function renewRegistration(int $years = 1, array $args = []): self
    {
        return DB::transaction(function () use ($years, $args) {
            $driver = $this->getRegistrarDriverFromProvider();
            $operation = $driver->renew($this->name, $years);

            $this->applyOperationResult($operation);
            $this->expires_at = $this->determineExpiration($operation['expires_at'] ?? null, $years);

            $subscription = $this->ensureSubscription($args + ['years' => $years, 'expires_at' => $this->expires_at]);
            $this->recordFinancialOperation($subscription, $args, $operation, 'manual');

            $this->save();

            return $this->fresh();
        });
    }

    public function initiateTransfer(array $args): self
    {
        return DB::transaction(function () use ($args) {
            $driver = $this->getRegistrarDriverFromProvider();

            if (!method_exists($driver, 'transferDomain')) {
                throw new \BadMethodCallException('El driver del registrador no soporta transferencias.');
            }

            $payload = $args;
            $payload['domain'] = $this->name;

            $operation = $driver->transferDomain($payload);
            $this->applyOperationResult($operation);

            $years = max(1, (int) ($args['years'] ?? 1));
            $this->expires_at = $this->determineExpiration($operation['expires_at'] ?? null, $years);

            if (!empty($args['contact']) && is_array($args['contact'])) {
                $this->storeContact($args['contact']);
            }

            $subscription = $this->ensureSubscription($args + ['years' => $years, 'expires_at' => $this->expires_at]);
            $this->recordFinancialOperation($subscription, $args, $operation, 'transfer');

            $this->save();

            return $this->fresh();
        });
    }

    public function releaseDomainRegistration(array $payload = []): self
    {
        return DB::transaction(function () use ($payload) {
            $driver = $this->getRegistrarDriverFromProvider();

            if (!method_exists($driver, 'releaseDomain')) {
                throw new \BadMethodCallException('El driver del registrador no soporta liberar dominios.');
            }

            $operation = $driver->releaseDomain($this->name, $payload);
            $this->status = 'released';
            $this->provider_status = strtoupper($operation['status'] ?? 'RELEASED');
            $this->auto_renew = false;

            if ($subscription = $this->subscription()->first()) {
                $subscription->update([
                    'status' => 'cancelled',
                    'auto_renewal' => false,
                    'cancel_at_end_date' => true,
                    'end_date' => $this->expires_at ?? Carbon::now(),
                ]);
            }

            $this->save();

            return $this->fresh();
        });
    }

    public function syncRegistrarState(): array
    {
        $driver = $this->getRegistrarDriverFromProvider();

        if (!method_exists($driver, 'getDomain')) {
            throw new \BadMethodCallException('El driver del registrador no expone el método getDomain.');
        }

        $state = $driver->getDomain($this->name);

        if (!empty($state['status'])) {
            $this->status = strtolower((string) $state['status']);
            $this->provider_status = strtoupper((string) $state['status']);
        }

        if (!empty($state['auto_renew'])) {
            $this->auto_renew = (bool) $state['auto_renew'];
        }

        if (!empty($state['nameservers'])) {
            $this->nameservers = $state['nameservers'];
        }

        if (!empty($state['expires_at'])) {
            $this->expires_at = $this->parseDateValue($state['expires_at']);
        }

        if ($subscription = $this->subscription()->first()) {
            $subscription->update([
                'auto_renewal' => $this->auto_renew,
                'end_date' => $this->expires_at,
            ]);
        }

        $this->save();

        return $state;
    }

    protected function applyOperationResult(array $operation): void
    {
        if (!empty($operation['operation_id'])) {
            $this->provider_ref = $operation['operation_id'];
        }

        if (!empty($operation['status'])) {
            $status = strtoupper((string) $operation['status']);
            $this->provider_status = $status;

            if (in_array($status, ['SUCCESS', 'ACTIVE', 'COMPLETED'], true)) {
                $this->status = 'active';
            } elseif (in_array($status, ['FAILED', 'ERROR'], true)) {
                $this->status = 'error';
            }
        }
    }

    protected function storeContact(array $contact): void
    {
        $this->contacts()->updateOrCreate(
            ['type' => 'registrant'],
            [
                'name' => $contact['first_name'] ?? ($contact['name'] ?? ''),
                'lastname' => $contact['last_name'] ?? ($contact['lastname'] ?? ''),
                'organization' => $contact['organization'] ?? ($contact['company'] ?? ''),
                'address' => $contact['address'] ?? ($contact['address1'] ?? ''),
                'city' => $contact['city'] ?? '',
                'state' => $contact['state'] ?? ($contact['region'] ?? ''),
                'zip' => $contact['zip'] ?? ($contact['postal_code'] ?? ''),
                'country' => strtoupper($contact['country'] ?? 'US'),
                'phone' => $contact['phone'] ?? '',
                'email' => $contact['email'] ?? '',
            ]
        );
    }

    protected function normalizeDnsRecords(array $records): array
    {
        return array_map(function ($record) {
            $value = $record['value'] ?? null;
            if ($value === null && isset($record['alias'])) {
                $value = json_encode($record['alias']);
            } elseif (is_array($value)) {
                $value = implode(',', $value);
            }

            return [
                'type' => strtoupper((string) ($record['type'] ?? '')),
                'name' => (string) ($record['name'] ?? $this->name),
                'value' => (string) $value,
                'ttl' => (int) ($record['ttl'] ?? 300),
                'priority' => $record['priority'] ?? null,
            ];
        }, $records);
    }

    protected function replaceLocalDns(array $records): void
    {
        $this->dns()->delete();

        foreach ($records as $record) {
            $this->dns()->create($record);
        }
    }

    protected function parseDateValue($value): ?Carbon
    {
        if (empty($value)) {
            return null;
        }

        if ($value instanceof Carbon) {
            return $value;
        }

        return Carbon::parse($value);
    }

    protected function determineExpiration($value, int $years): ?Carbon
    {
        $date = $this->parseDateValue($value);

        if ($date) {
            return $date;
        }

        return Carbon::now()->addYears(max(1, $years));
    }

    protected function ensureSubscription(array $args): DomainSubscription
    {
        $subscription = $this->subscription()->first();
        $paymentMethod = $this->resolvePaymentMethod($args);
        $userId = $this->resolveUserId($args);
        $autoRenew = array_key_exists('auto_renew', $args) ? (bool) $args['auto_renew'] : ($subscription->auto_renewal ?? $this->auto_renew ?? true);
        $years = max(1, (int) ($args['years'] ?? ($subscription->renewal_unit ?? 1)));
        $endDate = $args['expires_at'] ?? $this->expires_at ?? Carbon::now()->addYears($years);

        if (!$subscription) {
            $subscription = $this->subscription()->create([
                'status' => Arr::get($args, 'subscription_status', 'active'),
                'renewal_cycle' => Arr::get($args, 'renewal_cycle', 'manual'),
                'renewal_unit' => Arr::get($args, 'renewal_unit', $years),
                'auto_renewal' => $autoRenew,
                'start_date' => Carbon::now(),
                'end_date' => $endDate,
                'cancel_at_end_date' => false,
                'user_id' => $userId,
                'domain_payment_method_id' => $paymentMethod->id,
            ]);
        } else {
            $subscription->fill([
                'status' => Arr::get($args, 'subscription_status', $subscription->status),
                'renewal_cycle' => Arr::get($args, 'renewal_cycle', $subscription->renewal_cycle),
                'renewal_unit' => Arr::get($args, 'renewal_unit', $subscription->renewal_unit),
                'auto_renewal' => $autoRenew,
                'domain_payment_method_id' => $paymentMethod->id,
            ]);

            if ($endDate) {
                $subscription->end_date = $endDate;
            }

            if (!$subscription->start_date) {
                $subscription->start_date = Carbon::now();
            }

            if (array_key_exists('cancel_at_end_date', $args)) {
                $subscription->cancel_at_end_date = (bool) $args['cancel_at_end_date'];
            }

            $subscription->save();
        }

        return $subscription;
    }

    protected function resolvePaymentMethod(array $args): DomainPaymentMethod
    {
        if (!empty($args['payment_method']) && $args['payment_method'] instanceof DomainPaymentMethod) {
            return $args['payment_method'];
        }

        if (!empty($args['domain_payment_method_id'])) {
            $paymentMethod = DomainPaymentMethod::find($args['domain_payment_method_id']);
            if ($paymentMethod) {
                return $paymentMethod;
            }
        }

        if ($this->relationLoaded('subscription') && $this->subscription && $this->subscription->paymentMethod) {
            return $this->subscription->paymentMethod;
        }

        if ($subscription = $this->subscription()->with('paymentMethod')->first()) {
            if ($subscription->paymentMethod) {
                return $subscription->paymentMethod;
            }
        }

        throw new InvalidArgumentException('Debes indicar un método de pago válido para el dominio.');
    }

    protected function resolveUserId(array $args): int
    {
        $user = $args['user'] ?? null;

        if ($user instanceof Authenticatable) {
            return $user->getAuthIdentifier();
        }

        if (is_numeric($user)) {
            return (int) $user;
        }

        if ($this->relationLoaded('subscription') && $this->subscription && $this->subscription->user_id) {
            return (int) $this->subscription->user_id;
        }

        if ($subscription = $this->subscription()->first()) {
            if ($subscription->user_id) {
                return (int) $subscription->user_id;
            }
        }

        throw new InvalidArgumentException('Se requiere el usuario asociado a la suscripción del dominio.');
    }

    protected function recordFinancialOperation(DomainSubscription $subscription, array $args, array $operation, string $type): ?DomainPayment
    {
        $status = strtoupper($operation['status'] ?? 'PENDING');
        $renewal = $subscription->renewals()->create([
            'type' => $type,
            'renewed_date' => Carbon::now(),
            'status' => $this->mapRenewalStatus($status),
            'next_due_date' => $this->expires_at ?? Carbon::now(),
            'notes' => Arr::get($operation, 'message', Arr::get($operation, 'notes')),
        ]);

        $amount = isset($args['amount']) ? (float) $args['amount'] : 0.0;
        $tax = isset($args['tax']) ? (float) $args['tax'] : 0.0;
        $transactionId = $args['transaction_id'] ?? null;

        if ($transactionId === null && $amount <= 0) {
            return null;
        }

        $paymentMethod = $this->resolvePaymentMethod($args);
        $processor = $args['processor'] ?? $paymentMethod->processor ?? 'manual';

        $payment = $renewal->payments()->create([
            'processor' => $processor,
            'transaction_id' => $transactionId ?? ('manual-' . $renewal->id),
            'amount' => $amount,
            'tax' => $tax,
            'domain_payment_method_id' => $paymentMethod->id,
        ]);

        $providerAmount = isset($args['provider_amount']) ? (float) $args['provider_amount'] : $amount;
        if ($providerAmount < 0) {
            $providerAmount = 0.0;
        }

        $provider = $this->provider()->first();
        if ($provider) {
            $provider->providerPayments()->create([
                'status' => $this->mapProviderStatus($status),
                'amount' => $providerAmount,
                'domain_payment_id' => $payment->id,
            ]);
        }

        return $payment;
    }

    protected function mapRenewalStatus(string $status): string
    {
        return match ($status) {
            'SUCCESS', 'ACTIVE', 'COMPLETED' => 'success',
            'FAILED', 'ERROR' => 'failed',
            default => 'pending',
        };
    }

    protected function mapProviderStatus(string $status): string
    {
        return match ($status) {
            'SUCCESS', 'ACTIVE', 'COMPLETED' => 'success',
            'FAILED', 'ERROR' => 'failed',
            default => 'pending',
        };
    }
}
