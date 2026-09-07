<?php

namespace Innoboxrr\DomainManager\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Innoboxrr\DomainManager\Models\Domain;
use Innoboxrr\DomainManager\Models\DomainDns;
use Innoboxrr\DomainManager\Policies\Concerns\WorkspaceAware;

class DomainDnsPolicy
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

        return $domain && $this->canAccessWorkspace($user, $this->workspaceIdFromDomain($domain));
    }

    public function viewAny(User $user)
    {
        return $this->index($user);
    }

    public function view(User $user, DomainDns $domainDns)
    {
        $domain = $this->resolveDomain($domainDns);

        return $domain && $this->canAccessWorkspace($user, $this->workspaceIdFromDomain($domain));
    }

    public function create(User $user)
    {
        $domain = $this->resolveDomainFromRequest();

        return $this->canManageRecords($user, $domain);
    }

    public function update(User $user, DomainDns $domainDns)
    {
        $domain = $this->resolveDomain($domainDns);

        return $this->canManageRecords($user, $domain);
    }

    public function delete(User $user, DomainDns $domainDns)
    {
        $domain = $this->resolveDomain($domainDns);

        return $this->canManageRecords($user, $domain);
    }

    public function restore(User $user, DomainDns $domainDns)
    {
        return false;
    }

    public function forceDelete(User $user, DomainDns $domainDns)
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

    protected function resolveDomain(DomainDns $domainDns): ?Domain
    {
        return $domainDns->relationLoaded('domain')
            ? $domainDns->getRelation('domain')
            : $domainDns->domain()->with('provider')->first();
    }

    protected function canManageRecords(User $user, ?Domain $domain): bool
    {
        if (!$domain || ! $this->canAccessWorkspace($user, $this->workspaceIdFromDomain($domain), ['owner', 'member'])) {
            return false;
        }

        return in_array($domain->status, ['active'], true);
    }
}

