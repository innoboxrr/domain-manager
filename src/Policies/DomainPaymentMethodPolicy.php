<?php

namespace Innoboxrr\DomainManager\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Innoboxrr\DomainManager\Models\DomainPaymentMethod;
use Innoboxrr\DomainManager\Policies\Concerns\WorkspaceAware;

class DomainPaymentMethodPolicy
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
        return $this->canAccessWorkspace($user, $this->requestWorkspaceId(), ['owner', 'member']);
    }

    public function viewAny(User $user)
    {
        return $this->index($user);
    }

    public function view(User $user, DomainPaymentMethod $domainPaymentMethod)
    {
        return $this->ownsPaymentMethod($user, $domainPaymentMethod)
            && $this->canAccessWorkspace($user, $this->requestWorkspaceId() ?? $this->workspaceFromMethod($domainPaymentMethod), ['owner', 'member']);
    }

    public function create(User $user)
    {
        return $this->canAccessWorkspace($user, $this->requestWorkspaceId(), ['owner']);
    }

    public function update(User $user, DomainPaymentMethod $domainPaymentMethod)
    {
        if (! $this->ownsPaymentMethod($user, $domainPaymentMethod)) {
            return false;
        }

        if (! $this->canAccessWorkspace($user, $this->workspaceFromMethod($domainPaymentMethod), ['owner'])) {
            return false;
        }

        return true;
    }

    public function delete(User $user, DomainPaymentMethod $domainPaymentMethod)
    {
        if (! $this->ownsPaymentMethod($user, $domainPaymentMethod)) {
            return false;
        }

        if (! $this->canAccessWorkspace($user, $this->workspaceFromMethod($domainPaymentMethod), ['owner'])) {
            return false;
        }

        return ! $this->hasActiveSubscriptions($domainPaymentMethod);
    }

    public function restore(User $user, DomainPaymentMethod $domainPaymentMethod)
    {
        return false;
    }

    public function forceDelete(User $user, DomainPaymentMethod $domainPaymentMethod)
    {
        return false;
    }

    public function export(User $user)
    {
        return $this->canAccessWorkspace($user, $this->requestWorkspaceId(), ['owner']);
    }

    protected function ownsPaymentMethod(User $user, DomainPaymentMethod $method): bool
    {
        return (int) $method->user_id === (int) $user->id;
    }

    protected function hasActiveSubscriptions(DomainPaymentMethod $method): bool
    {
        return $method->subscriptions()
            ->whereIn('status', ['active', 'pending'])
            ->exists();
    }

    protected function workspaceFromMethod(DomainPaymentMethod $method): ?int
    {
        $subscription = $method->relationLoaded('subscriptions')
            ? $method->getRelation('subscriptions')->first()
            : $method->subscriptions()->with('domain.provider')->first();

        return $subscription ? $this->workspaceIdThrough($subscription, ['domain']) : $this->requestWorkspaceId();
    }
}

