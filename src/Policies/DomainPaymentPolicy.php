<?php

namespace Innoboxrr\DomainManager\Policies;

use App\Models\User;
use Innoboxrr\DomainManager\Models\DomainPayment;
use Illuminate\Auth\Access\HandlesAuthorization;

class DomainPaymentPolicy
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

    public function view(User $user, DomainPayment $domainPayment)
    {
        return false;
    }

    public function create(User $user)
    {
        return false;
    }

    public function update(User $user, DomainPayment $domainPayment)
    {
        return false;
    }

    public function delete(User $user, DomainPayment $domainPayment)
    {
        return false;
    }

    public function restore(User $user, DomainPayment $domainPayment)
    {
        return false;
    }

    public function forceDelete(User $user, DomainPayment $domainPayment)
    {
        return false;
    }

    public function export(User $user)
    {
        return false;
    }

}
