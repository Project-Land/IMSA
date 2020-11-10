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

        $messages = [
            'name.required' => 'Unesite ime firme',
            'name.unique' => 'Već postoji firma sa takvim nazivom',
            'name.min' => 'Ime firme ne sme biti kraće od 3 karaktera',
            'name.max' => 'Ime firme ne sme biti duže od 100 karaktera',
            'logo.required' => 'Izaberite logo firme',
            'logo.max' => 'Logo fajl ne sme biti veći od 1MB',
            'logo.image' => 'Fajl mora biti u nekom od sledećih formata: jpeg, png, bmp, gif, svg, webp'
        ];

        Validator::make($input, [
            'name' => ['required', 'string', 'min:3', 'max:100', 'unique:teams'],
            'logo' => ['required', 'image', 'max:1024']
        ], $messages)->validateWithBag('createTeam');

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
