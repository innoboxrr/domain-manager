<?php

namespace Innoboxrr\DomainManager\Policies;

use App\Models\User;
use Innoboxrr\DomainManager\Models\DomainRenewal;
use Illuminate\Auth\Access\HandlesAuthorization;

class DomainRenewalPolicy
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

    public function view(User $user, DomainRenewal $domainRenewal)
    {
        return false;
    }

    public function create(User $user)
    {
        return false;
    }

    public function update(User $user, DomainRenewal $domainRenewal)
    {
        return false;
    }

    public function delete(User $user, DomainRenewal $domainRenewal)
    {
        return false;
    }

    public function restore(User $user, DomainRenewal $domainRenewal)
    {
        return false;
    }

    public function forceDelete(User $user, DomainRenewal $domainRenewal)
    {
        return false;
    }

    public function export(User $user)
    {
        return false;
    }

}
