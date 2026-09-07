<?php

namespace Innoboxrr\DomainManager\Policies\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Innoboxrr\DomainManager\Models\Domain;
use Innoboxrr\DomainManager\Models\DomainProvider;

trait WorkspaceAware
{
    protected function requestWorkspaceId(): ?int
    {
        $workspaceId = request()->input('workspace_id');

        if (!$workspaceId && request()->route()) {
            $route = request()->route();
            if (is_object($route)) {
                $workspaceId = $route->parameter('workspace_id') ?? $route->parameter('workspace');
            }
        }

        if ($workspaceId === null || $workspaceId === '') {
            return null;
        }

        return (int) $workspaceId;
    }

    protected function canAccessWorkspace(User $user, ?int $workspaceId, array $roles = ['owner', 'member']): bool
    {
        if (!$workspaceId) {
            return false;
        }

        return (bool) $user->workspacePermission($workspaceId, $roles);
    }

    protected function workspaceIdFromDomain(?Domain $domain): ?int
    {
        if (!$domain) {
            return null;
        }

        $provider = $domain->relationLoaded('provider')
            ? $domain->getRelation('provider')
            : $domain->provider()->first();

        return $provider?->workspace_id;
    }

    protected function workspaceIdFromProvider(?DomainProvider $provider): ?int
    {
        return $provider?->workspace_id;
    }

    protected function workspaceIdThrough(Model $model, array $path): ?int
    {
        $current = $model;

        foreach ($path as $relation) {
            if (!$current) {
                return null;
            }

            if (method_exists($current, $relation)) {
                $current = $current->relationLoaded($relation)
                    ? $current->getRelation($relation)
                    : $current->{$relation}()->first();
            } else {
                return null;
            }
        }

        if ($current instanceof Domain) {
            return $this->workspaceIdFromDomain($current);
        }

        if ($current instanceof DomainProvider) {
            return $this->workspaceIdFromProvider($current);
        }

        return null;
    }
}

