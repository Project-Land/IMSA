<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Soa;
use Illuminate\Auth\Access\HandlesAuthorization;

class SoaPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($role == "admin" || $role == "super-admin") {
            return true;
        }
    }

    public function update(User $user, Soa $soa)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($user->current_team_id === $soa->team_id){
            if($role == "admin" || $role == "super-admin") {
                return true;
            }
        }
    }

}
