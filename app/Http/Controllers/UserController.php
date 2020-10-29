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
use App\Facades\CustomLog;
use Exception;

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
        $users = User::where('current_team_id', \Auth::user()->current_team_id)->get();
        return view('users.index', compact('users'));
    }

    public function changeCurrentTeam($teamId)
    {
        $user = User::findOrFail(\Auth::user()->id);
        $user->update(['current_team_id' => $teamId]);
        
        session()->forget('standard');
        session()->forget('standard_name');
        return redirect('/');
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
        
        try{
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
            CustomLog::info('Kreiran novi nalog "'.$request->name.'" sa ulogom : "'.$role.'". Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Novi korisnik je uspešno kreiran!');
        }catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja korisnika email-'.$request['email'].'. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s').' Greška- '.$e->getMessage(), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Došlo je do greške, pokušajte ponovo!');
        }
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
