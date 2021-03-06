<?php

namespace App\Policies;

use App\Models\PlanIp;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlanIpPolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function before(User $user)
    {
        if  ($user->allTeams()->first()->membership->role=='super-admin') {
            return true;
        }
    }

    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PlanIp  $planIp
     * @return mixed
     */
    public function view(User $user, PlanIp $planIp)
    {
        if($user->current_team_id === $planIp->team_id){
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
        if(($role == "super-admin") || $user->certificates->pluck('name')->contains('editor_'.session('standard_name'))) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PlanIp  $planIp
     * @return mixed
     */
    public function update(User $user, PlanIp $planIp)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($user->current_team_id === $planIp->internalCheck->team_id){
            if(($role == "super-admin") || $user->certificates->pluck('name')->contains('editor_'.session('standard_name'))) {
                return true;
            }
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PlanIp  $planIp
     * @return mixed
     */
    public function delete(User $user, PlanIp $planIp)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($user->current_team_id === $planIp->internalCheck->team_id){
            if(($role == "super-admin") || $user->certificates->pluck('name')->contains('editor_'.session('standard_name'))) {
                return true;
            }
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PlanIp  $planIp
     * @return mixed
     */
    public function restore(User $user, PlanIp $planIp)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PlanIp  $planIp
     * @return mixed
     */
    public function forceDelete(User $user, PlanIp $planIp)
    {
        //
    }
}
