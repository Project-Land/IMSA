<?php

namespace App\Actions\Jetstream;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Jetstream\Contracts\UpdatesTeamNames;
use Illuminate\Support\Str;

class UpdateTeamName implements UpdatesTeamNames
{
    /**
     * Validate and update the given team's name.
     *
     * @param  mixed  $user
     * @param  mixed  $team
     * @param  array  $input
     * @return void
     */

    public function update($user, $team, array $input)
    {
        Gate::forUser($user)->authorize('update', $team);

        $messages = [
            'name.required' => 'Unesite ime firme',
            'name.unique' => 'Već postoji firma sa takvim nazivom',
            'name.min' => 'Ime firme ne sme kraće od 3 karaktera',
            'name.max' => 'Ime firme ne sme biti duže od 100 karaktera',
            'logo.max' => 'Logo fajl ne sme biti veći od 1MB',
            'logo.mimes' => 'Fajl mora biti u nekom od sledećih formata: jpg, jpeg, png, bmp, gif, svg'
        ];

        Validator::make($input, [
            'name' => ['required', 'string', 'min:3', 'max:100', Rule::unique('teams')->ignore($team->id)],
            'logo' => ['mimes:png,jpg,jpeg,bmp,gif,svg', 'max:1024']
        ], $messages)->validateWithBag('updateTeamName');  

        if(count($input) > 1){
            $filename = Str::kebab($input['name']).'-logo.'.$input['logo']->getClientOriginalExtension();
            $input['logo']->storeAs('public/logos', $filename);
            $team->forceFill([
                'name' => $input['name'],
                'logo' => $filename
            ])->save();
        }
        else{
            $team->forceFill([
                'name' => $input['name'],
            ])->save();
        }

    }
}
