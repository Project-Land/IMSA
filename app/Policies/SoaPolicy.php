<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Soa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\HandlesAuthorization;

class SoaPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($role == "admin" || $role == "super-admin") {
            $soas = Soa::where('team_id', Auth::user()->current_team_id)->with('soaField', 'documents')->get();
            if($soas->count() == 0){
                return true;
            }
        }
    }

    public function update(User $user)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($role == "admin" || $role == "super-admin") {
            $soas = Soa::where('team_id', Auth::user()->current_team_id)->with('soaField', 'documents')->get();
            if($soas->count() != 0){
                return true;
            }
        }
    }

}
