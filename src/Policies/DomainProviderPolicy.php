<?php

namespace Innoboxrr\DomainManager\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Innoboxrr\DomainManager\Models\DomainProvider;
use Innoboxrr\DomainManager\Policies\Concerns\WorkspaceAware;

class DomainProviderPolicy
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
        return $this->canAccessWorkspace($user, $this->requestWorkspaceId(), ['owner']);
    }

    public function viewAny(User $user)
    {
        return $this->index($user);
    }

    public function view(User $user, DomainProvider $domainProvider)
    {
        return $this->canAccessWorkspace($user, $this->workspaceIdFromProvider($domainProvider), ['owner', 'member']);
    }

    public function create(User $user)
    {
        return $this->canAccessWorkspace($user, $this->requestWorkspaceId(), ['owner']);
    }

    public function update(User $user, DomainProvider $domainProvider)
    {
        return $this->canAccessWorkspace($user, $this->workspaceIdFromProvider($domainProvider), ['owner']);
    }

    public function delete(User $user, DomainProvider $domainProvider)
    {
        if (! $this->canAccessWorkspace($user, $this->workspaceIdFromProvider($domainProvider), ['owner'])) {
            return false;
        }

        $hasActiveDomains = $domainProvider->domains()
            ->whereNull('deleted_at')
            ->whereIn('status', ['active', 'purchasing', 'transferring'])
            ->exists();

        return ! $hasActiveDomains;
    }

    public function restore(User $user, DomainProvider $domainProvider)
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, DomainProvider $domainProvider)
    {
        return false;
    }

    public function export(User $user)
    {
        return $this->canAccessWorkspace($user, $this->requestWorkspaceId(), ['owner']);
    }
}

