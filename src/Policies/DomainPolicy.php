<?php

namespace Innoboxrr\DomainManager\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Innoboxrr\DomainManager\Models\Domain;
use Innoboxrr\DomainManager\Policies\Concerns\WorkspaceAware;

class DomainPolicy
{
    use HandlesAuthorization;
    use WorkspaceAware;

    /** @var string[] */
    protected array $adminBypassAbilities = [
        'index',
        'viewAny',
        'view',
        'create',
        'update',
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
        return $this->canAccessWorkspace($user, $this->requestWorkspaceId());
    }

    public function viewAny(User $user)
    {
        return $this->index($user);
    }

    public function view(User $user, Domain $domain)
    {
        return $this->canAccessWorkspace($user, $this->workspaceIdFromDomain($domain));
    }

    public function create(User $user)
    {
        return $this->canAccessWorkspace($user, $this->requestWorkspaceId(), ['owner']);
    }

    public function update(User $user, Domain $domain)
    {
        if (! $this->canAccessWorkspace($user, $this->workspaceIdFromDomain($domain))) {
            return false;
        }

        return !in_array($domain->status, ['released'], true);
    }

    public function delete(User $user, Domain $domain)
    {
        if (! $this->canAccessWorkspace($user, $this->workspaceIdFromDomain($domain), ['owner'])) {
            return false;
        }

        if (in_array($domain->status, ['active', 'purchasing', 'transferring'], true)) {
            return false;
        }

        $subscription = $domain->relationLoaded('subscription')
            ? $domain->getRelation('subscription')
            : $domain->subscription()->first();

        if ($subscription && in_array($subscription->status, ['active', 'pending', 'trial'], true)) {
            return false;
        }

        return in_array($domain->status, ['draft', 'error', 'released'], true);
    }

    public function restore(User $user, Domain $domain)
    {
        return $this->delete($user, $domain);
    }

    public function forceDelete(User $user, Domain $domain)
    {
        return $this->delete($user, $domain);
    }

    public function export(User $user)
    {
        return $this->canAccessWorkspace($user, $this->requestWorkspaceId(), ['owner']);
    }

}
