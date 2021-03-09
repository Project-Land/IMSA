<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Jetstream\Events\TeamMemberAdded;
use App\Facades\CustomLog;
use Illuminate\Support\Facades\Auth;
use App\Models\UserNotificationTypes;
use App\Models\Certificate;

class UserController extends Controller
{
    use \App\Actions\Fortify\PasswordValidationRules;

    public function index()
    {
        $this->authorize('viewAny', User::class);
        $users = User::where('current_team_id', Auth::user()->current_team_id)->get();
        return view('users.index', compact('users'));
    }

    public function changeCurrentTeam($teamId)
    {
        $this->authorize('canChangeTeams', User::class);
        $user = User::findOrFail(Auth::user()->id);
        $user->update(['current_team_id' => $teamId]);

        session()->forget('standard');
        session()->forget('standard_name');
        return redirect('/');
    }

    public function create()
    {
        $this->authorize('create', User::class);
        $certificates = Certificate::all();
        return view('users.create', compact('certificates'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $messages = array(
            'name.required' => 'Unesite ime',
            'name.min' => 'Ime mora sadržati minimum 2 karaktera',
            'name.max' => 'Ime ne sme biti duže od 50 karaktera',
            'username.required' => 'Unesite korisničko ime',
            'username.min' => 'Korisničko ime mora sadržati minimum 4 karaktera',
            'username.max' => 'Korisničko ime ne sme biti duže od 35 karaktera',
            'username.alpha_dash' => 'Korisničko ime može sadržati samo slova, brojeve i specijale karaktere "-" i "_"',
            'username.unique' => 'Već postoji korisnik sa takvim korisničkim imenom',
            'email.unique' => 'Već postoji korisnik sa takvom email adresom',
            'password.required' => 'Unesite lozinku',
            'password.string' => 'Lozinka mora sadržati minimum 8 karaktera',
            'password.confirmed' => 'Lozinke se ne podudaraju',
        );

        $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'username' => ['required', 'string', 'alpha_dash', 'min:4', 'max:35', 'unique:users'],
            'email' => ['nullable', 'max:255', 'unique:users'],
            'password' => $this->passwordRules()
        ], $messages);

        $teamID = Auth::user()->current_team_id;

        try{
            $user = User::create([
                'name' => $request['name'],
                'username' => $request['username'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'current_team_id' => $teamID
            ]);

            if($request->certificates){
                $user->certificates()->sync($request->certificates);
            }

            $role = $request['role'];

            $team = Team::find($teamID);
            $team->users()->attach(
                $newTeamMember = User::where('username', $request['username'])->firstOrFail(),
                ['role' => $role]
            );
            TeamMemberAdded::dispatch($team, $newTeamMember);

            CustomLog::info('Kreiran novi nalog "'.$request->name.'" sa ulogom: "'.$role.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', 'Novi korisnik je uspešno kreiran!'));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja korisnika '.$request['name'].', '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }
        return redirect('/users');
    }

    public function show($id)
    {
        abort(404);
    }

    public function edit($id)
    {
        abort(404);
    }

    public function update(Request $request, $id)
    {
        abort(404);
    }

    public function destroy($id)
    {
        $this->authorize('delete', User::find($id));
        $user = User::findOrFail($id);

        try{
            User::destroy($id);
            $user->teams()->detach();
            CustomLog::info('Obrisan korisnički nalog "'.$user->name.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', 'Korisnički nalog je uspešno obrisan'));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja korisničkog naloga "'.$user->name.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška- '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', 'Došlo je do greške! Pokušajte ponovo.'));
        }
    }

    public function notification_settings_show()
    {
        $this->authorize('view_notification_settings', User::class);
        $selected = UserNotificationTypes::where('user_id', Auth::user()->id)->get();
        return view('users.notifications_settings', compact('selected'));
    }

    public function notification_settings(Request $request)
    {
        $this->authorize('view_notification_settings', User::class);

        $notification_types = $request->notification_types;

        $selected = UserNotificationTypes::where('user_id', Auth::user()->id)->get();

        if(!empty($notification_types)){
            //Removes types if deselected
            foreach($selected as $s){
                if(!in_array($s->notifiable_type, $notification_types)){
                    UserNotificationTypes::where('notifiable_type', $s->notifiable_type)->where('user_id', Auth::user()->id)->delete();
                }
            }

            //Adds new selected types
            foreach($notification_types as $n){
                if(!$selected->contains('notifiable_type', $n)){
                    $data['user_id'] = Auth::user()->id;
                    $data['notifiable_type'] = $n;
                    UserNotificationTypes::create($data);
                }
            }
            return back()->with('status', array('info', 'Sačuvano'));
        }
        else{
            UserNotificationTypes::where('user_id', Auth::user()->id)->delete();
            return back()->with('status', array('info', 'Sačuvano'));
        }
    }

    public function getUserCertificates($id)
    {
        $selectedCertificates = Certificate::whereHas('users', function($q) use($id) {
            $q->where('user_id', $id);
        })->get();
        return response()->json($selectedCertificates->pluck('id'));
    }

    public function updateUserCertificates(Request $request, $id)
    {
        $user = User::find($id);
        $selectedCertificates = $request->selecteditems;

        $user->certificates()->sync($selectedCertificates);
        return true;
    }
}
