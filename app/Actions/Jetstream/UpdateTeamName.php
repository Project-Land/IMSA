<?php

namespace App\Actions\Jetstream;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
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

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['mimes:png,jpg,jpeg,bmp,gif,svg', 'max:1024']
        ])->validateWithBag('updateTeamName');

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
