<?php

namespace App\Policies;

use App\Models\RiskManagement;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RiskManagementPolicy
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
     * @param  \App\Models\RiskManagement  $riskManagement
     * @return mixed
     */
    public function view(User $user, RiskManagement $riskManagement)
    {
        if($user->current_team_id === $riskManagement->team_id){
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
        elseif($user->certificates->where('name', 'risk-management')->count() > 0){
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RiskManagement  $riskManagement
     * @return mixed
     */
    public function update(User $user, RiskManagement $riskManagement)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($user->current_team_id === $riskManagement->team_id){
            if($role == "admin" || $role == "super-admin") {
                return true;
            }
            elseif($user->certificates->where('name', 'risk-management')->count() > 0){
                return true;
            }
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RiskManagement  $riskManagement
     * @return mixed
     */
    public function delete(User $user, RiskManagement $riskManagement)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($user->current_team_id === $riskManagement->team_id){
            if($role == "admin" || $role == "super-admin") {
                return true;
            }
            elseif($user->certificates->where('name', 'risk-management')->count() > 0){
                return true;
            }
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RiskManagement  $riskManagement
     * @return mixed
     */
    public function restore(User $user, RiskManagement $riskManagement)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RiskManagement  $riskManagement
     * @return mixed
     */
    public function forceDelete(User $user, RiskManagement $riskManagement)
    {
        //
    }
}
