<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Complaint;
use Illuminate\Auth\Access\HandlesAuthorization;

class ComplaintPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function before(User $user)
    {
        $role = $user->allTeams()->first()->membership->role;
        if ($role == "admin" || $role == "super-admin") {
            return true;
        }
    }

    public function create(User $user)
    {
        
    }

    public function update(User $user, Complaint $complaint)
    {
        
    }

    public function delete(User $user, Complaint $complaint)
    {

    }
}
