<?php

namespace App\Policies;

use App\Models\CorrectiveMeasure;
use App\Models\User;
use App\Models\Team;
use Illuminate\Auth\Access\HandlesAuthorization;

class CorrectiveMeasurePolicy
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
     * @param  \App\Models\CorrectiveMeasure  $correctiveMeasure
     * @return mixed
     */
    public function view(User $user, CorrectiveMeasure $correctiveMeasure)
    {
        if($user->current_team_id === $correctiveMeasure->team_id){
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
        if($role == "admin" || $role == "super-admin" || $user->certificates->pluck('name')->contains('editor_'.session('standard_name')) || $user->certificates->pluck('name')->contains('corrective-measures')) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CorrectiveMeasure  $correctiveMeasure
     * @return mixed
     */
    public function update(User $user, CorrectiveMeasure $correctiveMeasure)
    {
        /*if($user->id === $correctiveMeasure->inconsistency->InternalCheckReport->internalCheck->user->id){
            return true;
        }*/
        $role = $user->allTeams()->first()->membership->role;
        if($user->current_team_id === $correctiveMeasure->team->id){
            if($role == "admin" || $role == "super-admin" || $user->certificates->pluck('name')->contains('editor_'.session('standard_name')) || $user->certificates->pluck('name')->contains('corrective-measures')) {
                return true;
            }
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CorrectiveMeasure  $correctiveMeasure
     * @return mixed
     */
    public function delete(User $user, CorrectiveMeasure $correctiveMeasure)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($user->current_team_id === $correctiveMeasure->team_id){
            if($role == "admin" || $role == "super-admin" || $user->certificates->pluck('name')->contains('editor_'.session('standard_name')) || $user->certificates->pluck('name')->contains('corrective-measures')) {
                return true;
            }
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CorrectiveMeasure  $correctiveMeasure
     * @return mixed
     */
    public function restore(User $user, CorrectiveMeasure $correctiveMeasure)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CorrectiveMeasure  $correctiveMeasure
     * @return mixed
     */
    public function forceDelete(User $user, CorrectiveMeasure $correctiveMeasure)
    {
        //
    }
}
