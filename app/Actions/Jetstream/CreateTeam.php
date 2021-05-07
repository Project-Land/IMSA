<?php

namespace App\Actions\Jetstream;

use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Contracts\CreatesTeams;
use Laravel\Jetstream\Events\TeamMemberAdded;

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

        //drugi admin
        $otherAdmin = Auth::user()->id == 1 ? User::find(2) : User::find(1);

        $messages = [
            'name.required' => 'Unesite ime firme',
            'name.unique' => 'Već postoji firma sa takvim nazivom',
            'name.min' => 'Ime firme ne sme biti kraće od 3 karaktera',
            'name.max' => 'Ime firme ne sme biti duže od 100 karaktera',
            'logo.required' => 'Izaberite logo firme',
            'logo.max' => 'Logo fajl ne sme biti veći od 1MB',
            'logo.image' => 'Fajl mora biti u nekom od sledećih formata: jpeg, png, bmp, gif, svg, webp',
            'lang.required' => 'Izaberite primarni jezik firme'
        ];

        Validator::make($input, [
            'name' => ['required', 'string', 'min:3', 'max:100', 'unique:teams'],
            'logo' => ['required', 'image', 'max:1024'],
            'lang' => ['required']
        ], $messages)->validateWithBag('createTeam');

        $this->logo = $input['logo'];
        $filename = Str::kebab($input['name']).'-logo.'.$this->logo->getClientOriginalExtension();
        $this->logo->storeAs('public/logos', $filename);

        $this->lang = $input['lang'];

        $team = $user->ownedTeams()->create([
            'name' => $input['name'],
            'logo' => $filename,
            'personal_team' => false,
            'lang' => $input['lang']
        ]);

        $team->users()->attach(
            $newTeamMember = $user,
            ['role' => 'super-admin']
        );
        TeamMemberAdded::dispatch($team, $newTeamMember);

        //Drugi admin
        $team->users()->attach(
            $newTeamMember = $otherAdmin,
            ['role' => 'super-admin']
        );
        TeamMemberAdded::dispatch($team, $newTeamMember);

        $standard = \App\Models\Standard::where('name', 9001)->get();
        $team->standards()->attach($standard);

        \App\Models\Sector::create([
            'team_id' => $team->id,
            'user_id' => Auth::user()->id,
            'name' => 'Sistem menadžmenta',
            'is_global' => '1'
        ]);

        return $team;
    }
}
