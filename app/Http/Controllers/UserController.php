<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Events\TeamMemberAdded;
use Laravel\Jetstream\Jetstream;

class UserController extends Controller
{
    use \App\Actions\Fortify\PasswordValidationRules;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $current_team = \Auth::user()->current_team_id;
        $users = User::select('users.*', 'teams.name as team_name, teams.id as team_id, teams.user_id', 'team_user.role')->join('team_user', 'users.id', 'team_user.user_id')->join('teams', 'teams.id', 'team_user.team_id')->where('current_team_id', $current_team)->get();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $messages = array(
            'name.required' => 'Unesite ime',
            'name.max' => 'Polje ne sme biti duže od 255 karaktera',
            'email.required' => 'Unesite email adresu',
            'email.unique' => 'Već postoji korisnik sa takvom email adresom',
            'password.required' => 'Unesite lozinku',
            'password.string' => 'Lozinka mora sadržati minimum 8 karaktera',
            'password.confirmed' => 'Lozinke se ne podudaraju',
        );

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules()
        ], $messages);

        $teamID = \Auth::user()->current_team_id;

        $userID = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'current_team_id' => $teamID
        ]);

        $role = $request['role'];

        $team = Team::find($teamID);

        $team->users()->attach(
            $newTeamMember = Jetstream::findUserByEmailOrFail($request['email']),
            ['role' => $role]
        );

        TeamMemberAdded::dispatch($team, $newTeamMember);

        $request->session()->flash('status', 'Novi korisnik je uspešno kreiran!');
        return redirect('/users');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
