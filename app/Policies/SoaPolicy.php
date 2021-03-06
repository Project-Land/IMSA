<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Soa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\HandlesAuthorization;

class SoaPolicy
{
    use HandlesAuthorization;

    public function view(User $user)
    {
        $soas = Soa::where('team_id', Auth::user()->current_team_id)->with('soaField', 'documents')->get();
        if($soas->count() != 0 && session('standard_name') === "27001"){
            return true;
        }
    }

    public function create(User $user)
    {
        $role = $user->allTeams()->first()->membership->role;

        $soas = Soa::where('team_id', Auth::user()->current_team_id)->with('soaField', 'documents')->get();
        if($soas->count() == 0 && session('standard_name') === "27001"){
            if($role == "admin" || $role == "super-admin" || $user->certificates->pluck('name')->contains('statement-of-applicability')) {
                return true;
            }
        }

    }

    public function update(User $user)
    {
        $role = $user->allTeams()->first()->membership->role;
        $soas = Soa::where('team_id', Auth::user()->current_team_id)->with('soaField', 'documents')->get();
        if($soas->count() != 0 && session('standard_name') === "27001"){
            if($role == "admin" || $role == "super-admin" || $user->certificates->pluck('name')->contains('statement-of-applicability')) {
                return true;
            }
        }
    }
}
