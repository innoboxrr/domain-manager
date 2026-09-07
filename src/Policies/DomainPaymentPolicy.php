<?php

namespace Innoboxrr\DomainManager\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Innoboxrr\DomainManager\Models\Domain;
use Innoboxrr\DomainManager\Models\DomainPayment;
use Innoboxrr\DomainManager\Models\DomainRenewal;
use Innoboxrr\DomainManager\Models\DomainSubscription;
use Innoboxrr\DomainManager\Policies\Concerns\WorkspaceAware;

class DomainPaymentPolicy
{
    use HandlesAuthorization;
    use WorkspaceAware;

    /** @var string[] */
    protected array $adminBypassAbilities = [
        'index',
        'viewAny',
        'view',
        'export',
    ];

    public function before($user, $ability)
    {
        if ($user->isAdmin() && in_array($ability, $this->adminBypassAbilities, true)) {
            return true;
        }
    }

    public function index(User $user)
    {
        $workspaceId = $this->workspaceFromRequest();

        return $this->canAccessWorkspace($user, $workspaceId);
    }

    public function viewAny(User $user)
    {
        return $this->index($user);
    }

    public function view(User $user, DomainPayment $domainPayment)
    {
        $workspaceId = $this->workspaceIdThrough($domainPayment, ['renewal', 'subscription', 'domain']);

        return $this->canAccessWorkspace($user, $workspaceId);
    }

    public function create(User $user)
    {
        return false;
    }

    public function update(User $user, DomainPayment $domainPayment)
    {
        return false;
    }

    public function delete(User $user, DomainPayment $domainPayment)
    {
        return false;
    }

    public function restore(User $user, DomainPayment $domainPayment)
    {
        return false;
    }

    public function forceDelete(User $user, DomainPayment $domainPayment)
    {
        return false;
    }

    public function export(User $user)
    {
        return $this->canAccessWorkspace($user, $this->requestWorkspaceId(), ['owner']);
    }

    protected function workspaceFromRequest(): ?int
    {
        $renewalId = request()->input('domain_renewal_id');
        if ($renewalId) {
            $renewal = DomainRenewal::with('subscription.domain.provider')->find($renewalId);
            if ($renewal) {
                return $this->workspaceIdThrough($renewal, ['subscription', 'domain']);
            }
        }

        $subscriptionId = request()->input('domain_subscription_id');
        if ($subscriptionId) {
            $subscription = DomainSubscription::with('domain.provider')->find($subscriptionId);
            if ($subscription) {
                return $this->workspaceIdThrough($subscription, ['domain']);
            }
        }

        $domainId = request()->input('domain_id');
        if ($domainId) {
            $domain = Domain::with('provider')->find($domainId);
            if ($domain) {
                return $this->workspaceIdFromDomain($domain);
            }
        }

        return $this->requestWorkspaceId();
    }
}

