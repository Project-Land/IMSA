<?php

namespace App\Policies;

use App\Models\Sector;
use App\Models\User;
use App\Models\Team;
use Illuminate\Auth\Access\HandlesAuthorization;

class SectorPolicy
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
     * @param  \App\Models\Sector  $sector
     * @return mixed
     */
    public function view(User $user, Sector $sector)
    {
       
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
     * @param  \App\Models\Sector  $sector
     * @return mixed
     */
    public function update(User $user, Sector $sector)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($user->current_team_id === $sector->team_id){
            if($role == "admin" || $role == "super-admin") {
                return true;
            }
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Sector  $sector
     * @return mixed
     */
    public function delete(User $user, Sector $sector)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($user->current_team_id === $sector->team_id){
            if($role == "admin" || $role == "super-admin") {
                return true;
            }
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Sector  $sector
     * @return mixed
     */
    public function restore(User $user, Sector $sector)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Sector  $sector
     * @return mixed
     */
    public function forceDelete(User $user, Sector $sector)
    {
        //
    }
}
