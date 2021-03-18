<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SatisfactionColumn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\HandlesAuthorization;

class SatisfactionColumnsPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        $poll = SatisfactionColumn::where('team_id', $user->current_team_id)->count();
        $role = $user->allTeams()->first()->membership->role;
        if($role == "admin" || $role == "super-admin") {
            if(session('standard_name') === "9001"){
                if(!$poll){
                    return true;
                }
            }
        }
    }

    public function update(User $user)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($role == "admin" || $role == "super-admin") {
            if(session('standard_name') === "9001"){
                return true;
            }
        }
    }
}
