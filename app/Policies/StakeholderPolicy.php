<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Stakeholder;
use Illuminate\Auth\Access\HandlesAuthorization;

class StakeholderPolicy
{
    use HandlesAuthorization;

    public function before(User $user)
    {
        if($user->allTeams()->first()->membership->role==='super-admin' || $user->allTeams()->first()->membership->role==='admin' || $user->allTeams()->first()->membership->role==='editor')
        return true; 
    }

    /**
     * Create a new policy instance.
     *
     * @return void
     */


    public function create(User $user)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($role == "admin" || $role == "super-admin") {
            return true;
        }
    }

    public function update(User $user, Stakeholder $stakeholder)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($user->current_team_id === $stakeholder->team_id){
            if($role == "admin" || $role == "super-admin") {
                return true;
            }
        }
    }

    public function delete(User $user, Stakeholder $stakeholder)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($user->current_team_id === $stakeholder->team_id){
            if($role == "admin" || $role == "super-admin") {
                return true;
            }
        }
    }
}

