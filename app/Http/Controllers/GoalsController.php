<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Goal;
use App\Facades\CustomLog;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Requests\GoalsRequest;
use Illuminate\Support\Facades\Auth;

class GoalsController extends Controller
{

    public function index()
    {
        if(request()->has('standard') && request()->has('standard_name')){
            session(['standard' => request()->get('standard')]);
            session(['standard_name' => request()->get('standard_name')]);
        }

        if(empty(session('standard'))){
            return redirect('/')->with('status', array('secondary', 'Izaberite standard!'));
        }

        $goals = Goal::where([
                ['standard_id', session('standard')],
                ['team_id', Auth::user()->current_team_id]
            ])->get();

        return view('system_processes.goals.index', ['goals' => $goals]);
    }

    public function getData(Request $request)
    {
        $goals = Goal::where([
                ['standard_id', session('standard')],
                ['year', $request->data['year']],
                ['team_id', Auth::user()->current_team_id]
            ])->get();

        $isAdmin = Auth::user()->allTeams()->first()->membership->role == "admin" || Auth::user()->allTeams()->first()->membership->role == "super-admin" ? true : false;

        if(!$goals->isEmpty()){
            $goals = $goals->map(function($item, $key) use($isAdmin){
                $item->isAdmin = $isAdmin;
                
                return $item;
            });
        }
        return response()->json($goals);
    }

    public function create()
    {
        if(empty(session('standard'))){
            return redirect('/');
        }
        $this->authorize('create', Goal::class);
        return view('system_processes.goals.create');
    }

    public function store(GoalsRequest $request)
    {
        $this->authorize('create', Goal::class);

        try{
            $goal = Goal::create($request->all());

            $notification = Notification::create([
                    'message'=>__('Analiza cilja za ').date('d.m.Y', strtotime($goal->deadline)),
                    'team_id'=>Auth::user()->current_team_id,
                    'checkTime' => $goal->deadline
                ]);
            $goal->notification()->save($notification);

            CustomLog::info('Cilj id: "'.$goal->id.'" kreiran, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', 'Cilj je uspešno sačuvan!'));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja cilja, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }
        return redirect('/goals');
    }

    public function show($id)
    {
        if(!request()->expectsJson()){
            abort(404);
        }

        $goal = Goal::with('user')->findOrFail($id);
        $level=$goal->level < 3 ? ($goal->level == 2 ? __('Srednji') : __('Mali')) : __('Veliki');
        $goal->level=$level;
;        return response()->json($goal);
    }

    public function edit($id)
    {
        $goal = Goal::findOrFail($id);
        $this->authorize('update', $goal);
        return view('system_processes.goals.edit', ['goal' => $goal]);
    }

    public function update(GoalsRequest $request, $id)
    {
        $goal = Goal::findOrFail($id);
        $this->authorize('update', $goal);


        try{
            $goal->update($request->all());
            $notification = $goal->notification;
            if(!$notification){
                $notification=new Notification();
                $notification->team_id=Auth::user()->current_team_id;
            }
            $notification->message = __('Analiza cilja za ').date('d.m.Y', strtotime($request->deadline));
            $notification->checkTime = $goal->deadline;
            $goal->notification()->save($notification);

            CustomLog::info('Cilj id: "'.$goal->id.'" izmenjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', 'Cilj je uspešno izmenjen!'));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene cilja id: "'.$goal->id.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }
        return redirect('/goals');
    }

    public function destroy($id)
    {
        $goal = Goal::findOrFail($id);
        $this->authorize('delete', $goal);

        try{
            $goal->notification()->delete();
            Goal::destroy($id);
            CustomLog::info('Cilj id: "'.$goal->id.'" uklonjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', 'Cilj je uspešno uklonjen'));
        } catch (Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja cilja id: "'.$goal->id.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }
    }

    public function deleteApi($id)
    {
        $goal = Goal::findOrFail($id);
        $this->authorize('delete', $goal);

        try{
            $goal->notification()->delete();
            Goal::destroy($id);
            CustomLog::info('Cilj id: "'.$goal->id.'" uklonjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return true;
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja cilja id: "'.$goal->id.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            return false;
        }
    }
}
