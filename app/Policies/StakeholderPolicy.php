<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Stakeholder;
use Illuminate\Auth\Access\HandlesAuthorization;

class StakeholderPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Stakeholder $stakeholder)
    {
        if($user->current_team_id === $stakeholder->team_id){
            return true;
        }
    }

    public function create(User $user)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($role == "admin" || $role == "super-admin" || $user->certificates->pluck('name')->contains('stakeholders')) {
            return true;
        }
    }

    public function update(User $user, Stakeholder $stakeholder)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($user->current_team_id === $stakeholder->team_id){
            if($role == "admin" || $role == "super-admin" || $user->certificates->pluck('name')->contains('stakeholders')) {
                return true;
            }
        }
    }

    public function delete(User $user, Stakeholder $stakeholder)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($user->current_team_id === $stakeholder->team_id){
            if($role == "admin" || $role == "super-admin" || $user->certificates->pluck('name')->contains('stakeholders')) {
                return true;
            }
        }
    }
}

