<?php

namespace App\Policies;

use App\Models\InternalCheckReport;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InternalCheckReportPolicy
{
    use HandlesAuthorization;

    public function before(User $user)
    {
        if($user->allTeams()->first()->membership->role==='super-admin' || $user->allTeams()->first()->membership->role==='admin')
        return true; 
    }

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
     * @param  \App\Models\InternalCheckReport  $internalCheckReport
     * @return mixed
     */
    public function view(User $user, InternalCheckReport $internalCheckReport)
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
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\InternalCheckReport  $internalCheckReport
     * @return mixed
     */
    public function update(User $user, InternalCheckReport $internalCheckReport)
    {
        if($user->id===$internalCheckReport->internalCheck->user->id)
          return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\InternalCheckReport  $internalCheckReport
     * @return mixed
     */
    public function delete(User $user, InternalCheckReport $internalCheckReport)
    {
        if($user->id===$internalCheckReport->internalCheck->user->id)
          return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\InternalCheckReport  $internalCheckReport
     * @return mixed
     */
    public function restore(User $user, InternalCheckReport $internalCheckReport)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\InternalCheckReport  $internalCheckReport
     * @return mixed
     */
    public function forceDelete(User $user, InternalCheckReport $internalCheckReport)
    {
        //
    }
}
