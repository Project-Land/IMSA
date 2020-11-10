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
        $this->authorize('viewAny', User::class);
        $users = User::where('current_team_id', \Auth::user()->current_team_id)->get();
        return view('users.index', compact('users'));
    }

    public function changeCurrentTeam($teamId)
    {
        $this->authorize('canChangeTeams', User::class);
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
        $this->authorize('create', User::class);
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
        $this->authorize('create', User::class);

        $messages = array(
            'name.required' => 'Unesite ime',
            'name.min' => 'Ime mora sadržati minimum 2 karaktera',
            'name.max' => 'Ime ne sme biti duže od 50 karaktera',
            'username.required' => 'Unesite korisničko ime',
            'username.min' => 'Korisničko ime mora sadržati minimum 4 karaktera',
            'username.max' => 'Korisničko ime ne sme biti duže od 20 karaktera',
            'username.alpha_dash' => 'Korisničko ime može sadržati samo slova, brojeve i specijale karaktere "-" i "_"',
            'username.unique' => 'Već postoji korisnik sa takvim korisničkim imenom',
            'email.unique' => 'Već postoji korisnik sa takvom email adresom',
            'password.required' => 'Unesite lozinku',
            'password.string' => 'Lozinka mora sadržati minimum 8 karaktera',
            'password.confirmed' => 'Lozinke se ne podudaraju',
        );

        $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'username' => ['required', 'string', 'alpha_dash', 'min:4', 'max:20', 'unique:users'],
            'email' => ['nullable', 'max:255', 'unique:users'],
            'password' => $this->passwordRules()
        ], $messages);

        $teamID = \Auth::user()->current_team_id;
        
        try{
            $userID = User::create([
                'name' => $request['name'],
                'username' => $request['username'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'current_team_id' => $teamID
            ]);

            $role = $request['role'];

            $team = Team::find($teamID);
            $team->users()->attach(
                $newTeamMember = User::where('username', $request['username'])->firstOrFail(),
                ['role' => $role]
            );
            TeamMemberAdded::dispatch($team, $newTeamMember);

            CustomLog::info('Kreiran novi nalog "'.$request->name.'" sa ulogom: "'.$role.'", '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Novi korisnik je uspešno kreiran!');
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja korisnika '.$request['name'].', '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s').' Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
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
        $this->authorize('delete', User::find($id));
        $user = User::findOrFail($id);

        try{
            User::destroy($id);
            $user->teams()->detach();
            CustomLog::info('Obrisan korisnički nalog "'.$user->name.'", '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
            return back()->with('status', 'Korisnički nalog je uspešno obrisan');
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja korisničkog naloga "'.$user->name.'", '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška- '.$e->getMessage(), \Auth::user()->currentTeam->name);
            return back()->with('status', 'Došlo je do greške! Pokušajte ponovo.');
        }
    }
}
