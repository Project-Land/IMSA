<?php

namespace App\Policies;

use App\Models\InternalCheck;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InternalCheckPolicy
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
        if($user->allTeams()->first()->membership->role==='super-admin' || $user->allTeams()->first()->membership->role==='admin' || $user->allTeams()->first()->membership->role==='editor')
        return true; 
    }
    
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\InternalCheck  $internalCheck
     * @return mixed
     */
    public function view(User $user)
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
     * @param  \App\Models\InternalCheck  $internalCheck
     * @return mixed
     */
    public function update(User $user, InternalCheck $internalCheck)
    {
        
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\InternalCheck  $internalCheck
     * @return mixed
     */
    public function delete(User $user, InternalCheck $internalCheck)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\InternalCheck  $internalCheck
     * @return mixed
     */
    public function restore(User $user, InternalCheck $internalCheck)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\InternalCheck  $internalCheck
     * @return mixed
     */
    public function forceDelete(User $user, InternalCheck $internalCheck)
    {
        //
    }
}
