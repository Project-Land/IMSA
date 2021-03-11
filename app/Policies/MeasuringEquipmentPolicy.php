<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MeasuringEquipment;
use Illuminate\Auth\Access\HandlesAuthorization;

class MeasuringEquipmentPolicy
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

    public function create(User $user)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($role == "admin" || $role == "super-admin") {
            if(session('standard_name') === "9001"){
                return true;
            }
        }
    }

    public function update(User $user, MeasuringEquipment $measuring_equipment)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($user->current_team_id === $measuring_equipment->team_id){
            if($role == "admin" || $role == "super-admin") {
                if(session('standard_name') === "9001"){
                    return true;
                }
            }
        }
    }

    public function delete(User $user, MeasuringEquipment $measuring_equipment)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($user->current_team_id === $measuring_equipment->team_id){
            if($role == "admin" || $role == "super-admin") {
                if(session('standard_name') === "9001"){
                    return true;
                }
            }
        }
    }

}
