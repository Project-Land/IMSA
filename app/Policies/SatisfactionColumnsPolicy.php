<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SatisfactionColumn;
use Illuminate\Auth\Access\HandlesAuthorization;

class SatisfactionColumnsPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        $poll = SatisfactionColumn::where('team_id', $user->current_team_id)->count();
        $role = $user->allTeams()->first()->membership->role;
        if(session('standard_name') === "9001"){
            if(!$poll){
                if($role == "admin" || $role == "super-admin" || $user->certificates->pluck('name')->contains('customer-satisfaction')) {
                    return true;
                }
            }
        }
    }

    public function update(User $user)
    {
        $role = $user->allTeams()->first()->membership->role;
        if(session('standard_name') === "9001"){
            if($role == "admin" || $role == "super-admin" || $user->certificates->pluck('name')->contains('customer-satisfaction')) {
                return true;
            }
        }
    }
}
