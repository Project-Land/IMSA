<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\CustomerSatisfaction;

class CustomerSatisfactionPolicy
{
    use HandlesAuthorization;

    public function view(User $user,CustomerSatisfaction $cs)
    {
       if($cs->team_id == $user->current_team_id){
            if(session('standard_name') === "9001"){
                return true;
            }
        }
    }

    public function create(User $user)
    {
        $role = $user->allTeams()->first()->membership->role;
        if(session('standard_name') === "9001"){
            if($role == "admin" || $role == "super-admin" || $user->certificates->pluck('name')->contains('customer-satisfaction')) {
                return true;
            }
        }
    }

    public function update(User $user, CustomerSatisfaction $cs)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($user->current_team_id === $cs->team_id){
            if(session('standard_name') === "9001"){
                if($role == "admin" || $role == "super-admin" || $user->certificates->pluck('name')->contains('customer-satisfaction')) {
                    return true;
                }
            }
        }
    }

    public function delete(User $user, CustomerSatisfaction $cs)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($user->current_team_id === $cs->team_id){
            if(session('standard_name') === "9001"){
                if($role == "admin" || $role == "super-admin" || $user->certificates->pluck('name')->contains('customer-satisfaction')) {
                    return true;
                }
            }
        }
    }
}
