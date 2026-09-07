<?php

namespace Innoboxrr\DomainManager\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Innoboxrr\DomainManager\Models\DomainPayment;
use Innoboxrr\DomainManager\Models\DomainProviderPayment;
use Innoboxrr\DomainManager\Policies\Concerns\WorkspaceAware;

class DomainProviderPaymentPolicy
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

        return $this->canAccessWorkspace($user, $workspaceId, ['owner']);
    }

    public function viewAny(User $user)
    {
        return $this->index($user);
    }

    public function view(User $user, DomainProviderPayment $domainProviderPayment)
    {
        $workspaceId = $this->workspaceIdThrough($domainProviderPayment, ['provider']);

        return $this->canAccessWorkspace($user, $workspaceId, ['owner']);
    }

    public function create(User $user)
    {
        return false;
    }

    public function update(User $user, DomainProviderPayment $domainProviderPayment)
    {
        return false;
    }

    public function delete(User $user, DomainProviderPayment $domainProviderPayment)
    {
        return false;
    }

    public function restore(User $user, DomainProviderPayment $domainProviderPayment)
    {
        return false;
    }

    public function forceDelete(User $user, DomainProviderPayment $domainProviderPayment)
    {
        return false;
    }

    public function export(User $user)
    {
        return $this->canAccessWorkspace($user, $this->requestWorkspaceId(), ['owner']);
    }

    protected function workspaceFromRequest(): ?int
    {
        $domainPaymentId = request()->input('domain_payment_id');
        if ($domainPaymentId) {
            $payment = DomainPayment::with('renewal.subscription.domain.provider')->find($domainPaymentId);
            if ($payment) {
                return $this->workspaceIdThrough($payment, ['renewal', 'subscription', 'domain']);
            }
        }

        return $this->requestWorkspaceId();
    }
}

