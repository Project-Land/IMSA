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
        if(session('standard') == null){
            return redirect('/')->with('status', 'Izaberite standard!');
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
        $this->authorize('create', Goal::class);
        return view('system_processes.goals.create');
    }

    public function store(GoalsRequest $request)
    {
        $this->authorize('create', Goal::class);

        try{
            $goal = Goal::create($request->all());

            $notification = Notification::create([
                    'message'=>'Analiza cilja za '.date('d.m.Y', strtotime($goal->deadline)),
                    'team_id'=>Auth::user()->current_team_id,
                    'checkTime' => $goal->deadline
                ]);
            $goal->notification()->save($notification);

            CustomLog::info('Cilj "'.$goal->goal.'" kreiran, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Cilj je uspešno sačuvan!');
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja cilja, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('warning', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/goals');
    }

    public function show($id)
    {
        if(!request()->expectsJson()){
            abort(404);
        }

        $goal = Goal::findOrFail($id);
        return response()->json($goal);
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
            $notification->message = 'Analiza cilja za '.date('d.m.Y', strtotime($request->deadline));
            $notification->checkTime = $goal->deadline;
            $goal->notification()->save($notification);

            CustomLog::info('Cilj "'.$goal->goal.'" izmenjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Cilj je uspešno izmenjen!');
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene cilja "'.$goal->goal.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('warning', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/goals');
    }

    public function destroy($id)
    {
        $goal = Goal::findOrFail($id);
        $this->authorize('delete', $goal);

        try{
            Goal::destroy($id);
            CustomLog::info('Cilj "'.$goal->goal.'" uklonjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', 'Cilj je uspešno uklonjen');
        } catch (Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja cilja "'.$goal->goal.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('warning', 'Došlo je do greške! Pokušajte ponovo.');
        }
    }

    public function deleteApi($id)
    {
        $goal = Goal::findOrFail($id);
        $this->authorize('delete', $goal);

        try{
            $goal->notification()->delete();
            Goal::destroy($id);
            CustomLog::info('Cilj "'.$goal->name.'" uklonjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return true;
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja cilja "'.$goal->goal.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            return false;
        }
    }
}
