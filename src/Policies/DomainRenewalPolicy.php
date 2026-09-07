<?php

namespace Innoboxrr\DomainManager\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Innoboxrr\DomainManager\Models\DomainRenewal;
use Innoboxrr\DomainManager\Models\DomainSubscription;
use Innoboxrr\DomainManager\Policies\Concerns\WorkspaceAware;

class DomainRenewalPolicy
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
        $subscription = $this->resolveSubscriptionFromRequest();
        $workspaceId = $subscription ? $this->workspaceIdThrough($subscription, ['domain']) : $this->requestWorkspaceId();

        return $this->canAccessWorkspace($user, $workspaceId);
    }

    public function viewAny(User $user)
    {
        return $this->index($user);
    }

    public function view(User $user, DomainRenewal $domainRenewal)
    {
        $workspaceId = $this->workspaceIdThrough($domainRenewal, ['subscription', 'domain']);

        return $this->canAccessWorkspace($user, $workspaceId);
    }

    public function create(User $user)
    {
        return false;
    }

    public function update(User $user, DomainRenewal $domainRenewal)
    {
        return false;
    }

    public function delete(User $user, DomainRenewal $domainRenewal)
    {
        return false;
    }

    public function restore(User $user, DomainRenewal $domainRenewal)
    {
        return false;
    }

    public function forceDelete(User $user, DomainRenewal $domainRenewal)
    {
        return false;
    }

    public function export(User $user)
    {
        return $this->canAccessWorkspace($user, $this->requestWorkspaceId(), ['owner']);
    }

    protected function resolveSubscriptionFromRequest(): ?DomainSubscription
    {
        $subscriptionId = request()->input('domain_subscription_id');

        if (!$subscriptionId && request()->route()) {
            $route = request()->route();
            if (is_object($route)) {
                $subscriptionId = $route->parameter('domain_subscription_id');
            }
        }

        if (!$subscriptionId) {
            return null;
        }

        return DomainSubscription::with('domain.provider')->find($subscriptionId);
    }
}

