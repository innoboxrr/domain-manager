<?php

namespace Innoboxrr\DomainManager\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Innoboxrr\DomainManager\Models\DomainTld;
use Innoboxrr\DomainManager\Policies\Concerns\WorkspaceAware;

class DomainTldPolicy
{
    use HandlesAuthorization;
    use WorkspaceAware;

    public function index(User $user)
    {
        return $this->canAccessWorkspace($user, $this->requestWorkspaceId(), ['owner', 'member']);
    }

    public function viewAny(User $user)
    {
        return $this->index($user);
    }

    public function view(User $user, DomainTld $domainTld)
    {
        return $this->canAccessWorkspace($user, $this->requestWorkspaceId(), ['owner', 'member']);
    }

    public function create(User $user)
    {
        return $user->isAdmin();
    }

    public function update(User $user, DomainTld $domainTld)
    {
        return $user->isAdmin();
    }

    public function delete(User $user, DomainTld $domainTld)
    {
        return false;
    }

    public function restore(User $user, DomainTld $domainTld)
    {
        return false;
    }

    public function forceDelete(User $user, DomainTld $domainTld)
    {
        return false;
    }

    public function export(User $user)
    {
        return $user->isAdmin();
    }
}

