<?php

namespace App\Policies;

use App\Models\CertDocument;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CertDocumentPolicy
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
     * @param  \App\Models\CertDocument  $certDocument
     * @return mixed
     */
    public function view(User $user, CertDocument $certDocument)
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
        elseif($user->certificates->where('name', 'upload')->count() > 0){
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CertDocument  $certDocument
     * @return mixed
     */
    public function update(User $user, CertDocument $certDocument)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($user->current_team_id === $certDocument->team_id){
            if($role == "admin" || $role == "super-admin" || $user->certificates->pluck('name')->contains('upload')) {
                return true;
            }
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CertDocument  $certDocument
     * @return mixed
     */
    public function delete(User $user, CertDocument $certDocument)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($user->current_team_id === $certDocument->team_id){
            if($role == "admin" || $role == "super-admin" || $user->certificates->pluck('name')->contains('upload')) {
                return true;
            }
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CertDocument  $certDocument
     * @return mixed
     */
    public function restore(User $user, CertDocument $certDocument)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CertDocument  $certDocument
     * @return mixed
     */
    public function forceDelete(User $user, CertDocument $certDocument)
    {
        //
    }
}
