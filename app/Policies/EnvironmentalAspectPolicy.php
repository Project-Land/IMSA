<?php

namespace App\Policies;

use App\Models\User;
use App\Models\EnvironmentalAspect;
use Illuminate\Auth\Access\HandlesAuthorization;

class EnvironmentalAspectPolicy
{
    use HandlesAuthorization;

    public function view(User $user, EnvironmentalAspect $environmental_aspect)
    {
        if($user->current_team_id === $environmental_aspect->team_id){
            return true;
        }
    }

    public function create(User $user)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($role == "admin" || $role == "super-admin") {
            return true;
        }
        elseif($user->certificates->where('name', 'environmental-aspects')->count() > 0){
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
            elseif($user->certificates->where('name', 'environmental-aspects')->count() > 0){
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
            elseif($user->certificates->where('name', 'environmental-aspects')->count() > 0){
                return true;
            }
        }
    }
}
