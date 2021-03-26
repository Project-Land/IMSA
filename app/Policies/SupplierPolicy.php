<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Supplier;
use Illuminate\Auth\Access\HandlesAuthorization;

class SupplierPolicy
{
    use HandlesAuthorization;


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
     * @param  \App\Models\Supplier  $supplier
     * @return mixed
     */
    public function view(User $user, Supplier $supplier)
    {
        if($user->current_team_id === $supplier->team_id){
            return true;
        }
    }

    public function create(User $user)
    {
        $role = $user->allTeams()->first()->membership->role;
        if ($role == "admin" || $role == "super-admin") {
            return true;
        }
        elseif($user->certificates->where('name', 'suppliers')->count() > 0){
            return true;
        }
    }

    public function update(User $user, Supplier $supplier)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($user->current_team_id === $supplier->team_id){
            if($role == "admin" || $role == "super-admin") {
                return true;
            }
            elseif($user->certificates->where('name', 'suppliers')->count() > 0){
                return true;
            }
        }
    }

    public function delete(User $user, Supplier $supplier)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($user->current_team_id === $supplier->team_id){
            if($role == "admin" || $role == "super-admin") {
                return true;
            }
            elseif($user->certificates->where('name', 'suppliers')->count() > 0){
                return true;
            }
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Supplier  $supplier
     * @return mixed
     */
    public function restore(User $user, Supplier $supplier)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Supplier  $supplier
     * @return mixed
     */
    public function forceDelete(User $user, Supplier $supplier)
    {
        //
    }
}
