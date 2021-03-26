<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MeasuringEquipment;
use Illuminate\Auth\Access\HandlesAuthorization;

class MeasuringEquipmentPolicy
{
    use HandlesAuthorization;

    public function view(User $user, MeasuringEquipment $measuring_equipment)
    {
        if($user->current_team_id === $measuring_equipment->team_id){
            return true;
        }
    }

    public function create(User $user)
    {
        $role = $user->allTeams()->first()->membership->role;
        if(session('standard_name') === "9001"){
            if($role == "admin" || $role == "super-admin") {
                return true;
            }
            elseif($user->certificates->where('name', 'measuring-equipment')->count() > 0){
                return true;
            }
        }

    }

    public function update(User $user, MeasuringEquipment $measuring_equipment)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($user->current_team_id === $measuring_equipment->team_id){
            if(session('standard_name') === "9001"){
                if($role == "admin" || $role == "super-admin") {
                    return true;
                }
                elseif($user->certificates->where('name', 'measuring-equipment')->count() > 0){
                    return true;
                }
            }
        }
    }

    public function delete(User $user, MeasuringEquipment $measuring_equipment)
    {
        $role = $user->allTeams()->first()->membership->role;
        if($user->current_team_id === $measuring_equipment->team_id){
            if(session('standard_name') === "9001"){
                if($role == "admin" || $role == "super-admin") {
                    return true;
                }
                elseif($user->certificates->where('name', 'measuring-equipment')->count() > 0){
                    return true;
                }
            }
        }
    }

}
