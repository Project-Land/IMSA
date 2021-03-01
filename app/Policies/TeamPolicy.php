<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
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
        //return true;
        return $user->hasTeamRole($user->currentTeam, 'super-admin');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return mixed
     */
    public function view(User $user, Team $team)
    {
        //return $user->belongsToTeam($team);
        return $user->hasTeamRole($user->currentTeam, 'super-admin');
    }

    public function viewAllTeams(User $user){

        return $user->hasTeamRole($user->currentTeam, 'super-admin');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasTeamRole($user->currentTeam, 'super-admin');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return mixed
     */
    public function update(User $user, Team $team)
    {
        //return $user->ownsTeam($team);
        return $user->hasTeamRole($user->currentTeam, 'super-admin') || $user->hasTeamRole($user->currentTeam, 'admin');
    }

    /**
     * Determine whether the user can add team members.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return mixed
     */
    public function addTeamMember(User $user, Team $team)
    {
        //return $user->ownsTeam($team);
        return $user->hasTeamRole($user->currentTeam, 'super-admin') || $user->hasTeamRole($user->currentTeam, 'admin');
    }

    /**
     * Determine whether the user can update team member permissions.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return mixed
     */
    public function updateTeamMember(User $user, Team $team)
    {
        //return $user->ownsTeam($team);
        return $user->hasTeamRole($user->currentTeam, 'super-admin') || $user->hasTeamRole($user->currentTeam, 'admin');
    }

    /**
     * Determine whether the user can remove team members.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return mixed
     */
    public function removeTeamMember(User $user, Team $team)
    {
        //return $user->ownsTeam($team);
        return $user->hasTeamRole($user->currentTeam, 'super-admin');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return mixed
     */
    public function delete(User $user, Team $team)
    {
        //return $user->ownsTeam($team);
        return $user->hasTeamRole($user->currentTeam, 'super-admin');
    }

    public function userManagement(User $user, Team $team)
    {
        return $user->hasTeamRole($user->currentTeam, 'super-admin') || $user->hasTeamRole($user->currentTeam, 'admin');
    }

    public function SystemProcessesManagement(User $user, Team $team)
    {
        return $user->hasTeamRole($user->currentTeam, 'super-admin');
    }

    public function serverInfo(User $user)
    {
        return $user->hasTeamRole($user->currentTeam, 'super-admin');
    }
}
