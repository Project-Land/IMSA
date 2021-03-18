<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Team;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\CustomerSatisfaction;
use Illuminate\Support\Facades\Auth;

class CustomerSatisfactionPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($role == "admin" || $role == "super-admin") {
            if(session('standard_name') === "9001"){
                return true;
            }
        }
    }

    public function update(User $user, CustomerSatisfaction $cs)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($user->current_team_id === $cs->team_id){
            if($role == "admin" || $role == "super-admin") {
                if(session('standard_name') == "9001"){
                    return true;
                }
            }
        }
    }

    public function delete(User $user, CustomerSatisfaction $cs)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($user->current_team_id === $cs->team_id){
            if($role == "admin" || $role == "super-admin") {
                if(session('standard_name') == "9001"){
                    return true;
                }
            }
        }
    }
}