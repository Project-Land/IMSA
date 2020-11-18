<?php

namespace App\Policies;

use App\Models\EvaluationOfLegalAndOtherRequirement;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EvaluationOfLegalAndOtherRequirementPolicy
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
     * @param  \App\Models\EvaluationOfLegalAndOtherRequirement  $evaluationOfLegalAndOtherRequirement
     * @return mixed
     */
    public function view(User $user, EvaluationOfLegalAndOtherRequirement $evaluationOfLegalAndOtherRequirement)
    {
        //
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
     * @param  \App\Models\EvaluationOfLegalAndOtherRequirement  $evaluationOfLegalAndOtherRequirement
     * @return mixed
     */
    public function update(User $user, EvaluationOfLegalAndOtherRequirement $evaluationOfLegalAndOtherRequirement)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($user->current_team_id === $evaluationOfLegalAndOtherRequirement->team_id){
            if($role == "admin" || $role == "super-admin") {
                return true;
            }
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EvaluationOfLegalAndOtherRequirement  $evaluationOfLegalAndOtherRequirement
     * @return mixed
     */
    public function delete(User $user, EvaluationOfLegalAndOtherRequirement $evaluationOfLegalAndOtherRequirement)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($user->current_team_id === $evaluationOfLegalAndOtherRequirement->team_id){
            if($role == "admin" || $role == "super-admin") {
                return true;
            }
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EvaluationOfLegalAndOtherRequirement  $evaluationOfLegalAndOtherRequirement
     * @return mixed
     */
    public function restore(User $user, EvaluationOfLegalAndOtherRequirement $evaluationOfLegalAndOtherRequirement)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EvaluationOfLegalAndOtherRequirement  $evaluationOfLegalAndOtherRequirement
     * @return mixed
     */
    public function forceDelete(User $user, EvaluationOfLegalAndOtherRequirement $evaluationOfLegalAndOtherRequirement)
    {
        //
    }
}
