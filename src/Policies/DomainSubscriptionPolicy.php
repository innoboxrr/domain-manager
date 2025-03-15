<?php

namespace Innoboxrr\DomainManager\Policies;

use App\Models\User;
use Innoboxrr\DomainManager\Models\DomainSubscription;
use Illuminate\Auth\Access\HandlesAuthorization;

class DomainSubscriptionPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {

        $exceptAbilities = [];

        if($user->isAdmin() && !in_array($ability, $exceptAbilities)){
        
            return true;
            
        }

    }

    public function index(User $user)
    {
        return false;
    }

    public function viewAny(User $user)
    {
        return false;
    }

    public function view(User $user, DomainSubscription $domainSubscription)
    {
        return false;
    }

    public function create(User $user)
    {
        return false;
    }

    public function update(User $user, DomainSubscription $domainSubscription)
    {
        return false;
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
        return false;
    }

}
