<?php

namespace App\Policies;

use App\Models\Training;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TrainingPolicy
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
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Training  $training
     * @return mixed
     */
    public function view(User $user, Training $training)
    {
        if($user->current_team_id === $training->team_id){
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
        if ($role == "admin" || $role == "super-admin" || $user->certificates->pluck('name')->contains('trainings')) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Training  $training
     * @return mixed
     */
    public function update(User $user, Training $training)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($user->current_team_id === $training->team_id){
            if($role == "admin" || $role == "super-admin" || $user->certificates->pluck('name')->contains('trainings')) {
                return true;
            }
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Training  $training
     * @return mixed
     */
    public function delete(User $user, Training $training)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($user->current_team_id === $training->team_id){
            if($role == "admin" || $role == "super-admin" || $user->certificates->pluck('name')->contains('trainings')) {
                return true;
            }
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Training  $training
     * @return mixed
     */
    public function restore(User $user, Training $training)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Training  $training
     * @return mixed
     */
    public function forceDelete(User $user, Training $training)
    {
        //
    }
}
