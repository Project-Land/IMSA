<?php

namespace App\Actions\Jetstream;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Contracts\CreatesTeams;
use Laravel\Jetstream\Events\TeamMemberAdded;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreateTeam implements CreatesTeams
{
    /**
     * Validate and create a new team for the given user.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return mixed
     */
    public $logo;

    public function create($user, array $input)
    {
        Gate::forUser($user)->authorize('create', Jetstream::newTeamModel());

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['required', 'mimes:png,jpg,jpeg,bmp,svg,gif', 'max:1024']
        ])->validateWithBag('createTeam');

        $this->logo = $input['logo'];
        $filename = Str::kebab($input['name']).'-logo.'.$this->logo->getClientOriginalExtension();
        $this->logo->storeAs('public/logos', $filename);

        $team = $user->ownedTeams()->create([
            'name' => $input['name'],
            'logo' => $filename,
            'personal_team' => false,
        ]);

        $team->users()->attach(
            $newTeamMember = $user,
            ['role' => 'super-admin']
        );

        TeamMemberAdded::dispatch($team, $newTeamMember);

        return $team;
    }
}
