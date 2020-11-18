<?php

namespace App\Policies;

use App\Models\User;
use App\Models\EnvironmentalAspect;
use Illuminate\Auth\Access\HandlesAuthorization;

class EnvironmentalAspectPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function create(User $user)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($role == "admin" || $role == "super-admin") {
            return true;
        }
    }

    public function update(User $user, EnvironmentalAspect $environmental_aspect)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($user->current_team_id === $environmental_aspect->team_id){
            if($role == "admin" || $role == "super-admin") {
                return true;
            }
        }
    }

    public function delete(User $user, EnvironmentalAspect $environmental_aspect)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($user->current_team_id === $environmental_aspect->team_id){
            if($role == "admin" || $role == "super-admin") {
                return true;
            }
        }
    }
}
