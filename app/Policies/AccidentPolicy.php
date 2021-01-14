<?php

namespace App\Policies;

use App\Models\Accident;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccidentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Accident  $accident
     * @return mixed
     */
    public function view(User $user, Accident $accident)
    {
        if($user->current_team_id === $accident->team_id){
            return true;
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($role == "admin" || $role == "super-admin") {
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Accident  $accident
     * @return mixed
     */
    public function update(User $user, Accident $accident)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($user->current_team_id === $accident->team_id){
            if($role == "admin" || $role == "super-admin") {
                return true;
            }
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Accident  $accident
     * @return mixed
     */
    public function delete(User $user, Accident $accident)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($user->current_team_id === $accident->team_id){
            if($role == "admin" || $role == "super-admin") {
                return true;
            }
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Accident  $accident
     * @return mixed
     */
    public function restore(User $user, Accident $accident)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Accident  $accident
     * @return mixed
     */
    public function forceDelete(User $user, Accident $accident)
    {
        //
    }
}
