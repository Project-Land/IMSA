<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SystemProcess;
use Illuminate\Auth\Access\HandlesAuthorization;

class SystemProcessPolicy
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
     * @param  \App\Models\SystemProcess  $SystemProcess
     * @return mixed
     */
    public function view(User $user, SystemProcess $SystemProcess)
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
        if ($role == "super-admin") {
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SystemProcess  $SystemProcess
     * @return mixed
     */
    public function update(User $user, SystemProcess $SystemProcess)
    {
        $role = $user->allTeams()->first()->membership->role;
        if ($role == "super-admin") {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SystemProcess  $SystemProcess
     * @return mixed
     */
    public function delete(User $user, SystemProcess $SystemProcess)
    {
        $role = $user->allTeams()->first()->membership->role;
        if ($role == "super-admin") {
            return true;
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SystemProcess  $SystemProcess
     * @return mixed
     */
    public function restore(User $user, SystemProcess $SystemProcess)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SystemProcess  $SystemProcess
     * @return mixed
     */
    public function forceDelete(User $user, SystemProcess $SystemProcess)
    {
        //
    }
}
