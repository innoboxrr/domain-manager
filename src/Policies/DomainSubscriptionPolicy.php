<?php

namespace Innoboxrr\DomainManager\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Innoboxrr\DomainManager\Models\Domain;
use Innoboxrr\DomainManager\Models\DomainSubscription;
use Innoboxrr\DomainManager\Policies\Concerns\WorkspaceAware;

class DomainSubscriptionPolicy
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

    public function view(User $user, DomainSubscription $domainSubscription)
    {
        $workspaceId = $this->workspaceIdThrough($domainSubscription, ['domain']);

        return $this->canAccessWorkspace($user, $workspaceId);
    }

    public function create(User $user)
    {
        return false;
    }

    public function update(User $user, DomainSubscription $domainSubscription)
    {
        $workspaceId = $this->workspaceIdThrough($domainSubscription, ['domain']);

        if (! $this->canAccessWorkspace($user, $workspaceId, ['owner'])) {
            return false;
        }

        $domain = $domainSubscription->relationLoaded('domain')
            ? $domainSubscription->getRelation('domain')
            : $domainSubscription->domain()->with('provider')->first();

        return $domain && $domain->status !== 'released';
    }

    public function delete(User $user, DomainSubscription $domainSubscription)
    {
        return false;
    }

    public function restore(User $user, DomainSubscription $domainSubscription)
    {
        return false;
    }

    public function forceDelete(User $user, DomainSubscription $domainSubscription)
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
}

