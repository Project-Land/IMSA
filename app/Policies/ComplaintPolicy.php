<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Complaint;
use Illuminate\Auth\Access\HandlesAuthorization;

class ComplaintPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Complaint $complaint)
    {
        if($user->current_team_id === $complaint->team_id){
            return true;
        }
    }

    public function create(User $user)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($role == "admin" || $role == "super-admin" || $user->certificates->pluck('name')->contains('complaints') || $user->certificates->pluck('name')->contains('editor')) {
            return true;
        }
    }

    public function update(User $user, Complaint $complaint)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($user->current_team_id === $complaint->team_id){
            if($role == "admin" || $role == "super-admin" || $user->certificates->pluck('name')->contains('complaints') || $user->certificates->pluck('name')->contains('editor')) {
                return true;
            }
        }
    }

    public function delete(User $user, Complaint $complaint)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($user->current_team_id === $complaint->team_id){
            if($role == "admin" || $role == "super-admin" || $user->certificates->pluck('name')->contains('complaints') || $user->certificates->pluck('name')->contains('editor')) {
                return true;
            }
        }
    }
}
