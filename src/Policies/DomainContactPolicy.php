<?php

namespace Innoboxrr\DomainManager\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Innoboxrr\DomainManager\Models\Domain;
use Innoboxrr\DomainManager\Models\DomainContact;
use Innoboxrr\DomainManager\Policies\Concerns\WorkspaceAware;

class DomainContactPolicy
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
        $domain = $this->resolveDomainFromRequest();
        $workspaceId = $domain ? $this->workspaceIdFromDomain($domain) : $this->requestWorkspaceId();

        return $this->canAccessWorkspace($user, $workspaceId);
    }

    public function viewAny(User $user)
    {
        return $this->index($user);
    }

    public function view(User $user, DomainContact $domainContact)
    {
        $workspaceId = $this->workspaceIdThrough($domainContact, ['domain']);

        return $this->canAccessWorkspace($user, $workspaceId);
    }

    public function create(User $user)
    {
        $domain = $this->resolveDomainFromRequest();

        if (! $this->canAccessWorkspace($user, $this->workspaceIdFromDomain($domain), ['owner'])) {
            return false;
        }

        return $this->domainAllowsContactMutation($domain);
    }

    public function update(User $user, DomainContact $domainContact)
    {
        $domain = $this->resolveDomain($domainContact);

        if (! $this->canAccessWorkspace($user, $this->workspaceIdFromDomain($domain), ['owner'])) {
            return false;
        }

        return $this->domainAllowsContactMutation($domain);
    }

    public function delete(User $user, DomainContact $domainContact)
    {
        $domain = $this->resolveDomain($domainContact);

        if (! $this->canAccessWorkspace($user, $this->workspaceIdFromDomain($domain), ['owner'])) {
            return false;
        }

        return in_array($domain->status, ['draft', 'error'], true);
    }

    public function restore(User $user, DomainContact $domainContact)
    {
        return false;
    }

    public function forceDelete(User $user, DomainContact $domainContact)
    {
        return false;
    }

    public function export(User $user)
    {
        return $this->canAccessWorkspace($user, $this->requestWorkspaceId(), ['owner']);
    }

    protected function resolveDomainFromRequest(): ?Domain
    {
        $domainId = request()->input('domain_id');

        if (!$domainId && request()->route()) {
            $route = request()->route();
            if (is_object($route)) {
                $domainId = $route->parameter('domain_id');
            }
        }

        if (!$domainId) {
            return null;
        }

        return Domain::with('provider')->find($domainId);
    }

    protected function resolveDomain(DomainContact $domainContact): ?Domain
    {
        return $domainContact->relationLoaded('domain')
            ? $domainContact->getRelation('domain')
            : $domainContact->domain()->with('provider')->first();
    }

    protected function domainAllowsContactMutation(?Domain $domain): bool
    {
        if (!$domain) {
            return false;
        }

        return !in_array($domain->status, ['released'], true);
    }
}

