<?php

namespace Innoboxrr\DomainManager\Policies;

use App\Models\User;
use Innoboxrr\DomainManager\Models\DomainPaymentMethod;
use Illuminate\Auth\Access\HandlesAuthorization;

class DomainPaymentMethodPolicy
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

    public function view(User $user, DomainPaymentMethod $domainPaymentMethod)
    {
        return false;
    }

    public function create(User $user)
    {
        return false;
    }

    public function update(User $user, DomainPaymentMethod $domainPaymentMethod)
    {
        return false;
    }

    public function delete(User $user, DomainPaymentMethod $domainPaymentMethod)
    {
        return false;
    }

    public function restore(User $user, DomainPaymentMethod $domainPaymentMethod)
    {
        return false;
    }

    public function forceDelete(User $user, DomainPaymentMethod $domainPaymentMethod)
    {
        return false;
    }

    public function export(User $user)
    {
        return false;
    }

}
