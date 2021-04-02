<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Goal;
use App\Models\User;
use App\Facades\CustomLog;
use Illuminate\Support\Str;
use App\Exports\GoalsExport;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Requests\GoalsRequest;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Notifications\InstantNotification;
use App\Notifications\GoalInstantNotification;

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

        $goals = Goal::with('users')->where([
                ['standard_id', session('standard')],
                ['year', date('Y')],
                ['team_id', Auth::user()->current_team_id]
            ])->get();

        return view('system_processes.goals.index', ['goals' => $goals]);
    }

    public function getData(Request $request)
    {
        if($request->data['year'] == 'all'){
            $goals = Goal::with('users')->where([
                ['standard_id', session('standard')],
                ['team_id', Auth::user()->current_team_id]
            ])->get();
        }
        else{
            $goals = Goal::with('users')->where([
                ['standard_id', session('standard')],
                ['year', $request->data['year']],
                ['team_id', Auth::user()->current_team_id]
            ])->get();
        }

        $isAdmin = Auth::user()->allTeams()->first()->membership->role == "admin" || Auth::user()->allTeams()->first()->membership->role == "super-admin" ? true : false;

        if(!$goals->isEmpty()){
            $goals = $goals->map(function($item, $key) use($isAdmin){
                $item->isAdmin = $isAdmin;
                $level = $item->levelIs();
                $item->level = $level;
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
        $users = User::with('teams')->where('current_team_id', Auth::user()->current_team_id)->get();
        return view('system_processes.goals.create', compact('users'));
    }

    public function store(GoalsRequest $request)
    {
        $this->authorize('create', Goal::class);

        try{
            $goal = Goal::create($request->except('responsibility'));
            $goal->users()->sync($request->responsibility);

            $notification = Notification::create([
                    'message'=>__('Analiza cilja za ').date('d.m.Y', strtotime($goal->deadline)),
                    'team_id'=>Auth::user()->current_team_id,
                    'checkTime' => $goal->deadline
                ]);
            $goal->notification()->save($notification);

            $not = new GoalInstantNotification($goal);
            $not->save();
            $not->user()->sync($request->responsibility);

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

        $goal = Goal::with('user', 'users')->findOrFail($id);
        $goal->level=$goal->levelIs();
;        return response()->json($goal);
    }

    public function edit($id)
    {
        $goal = Goal::findOrFail($id);
        $this->authorize('update', $goal);
        $users = User::with('teams')->where('current_team_id', Auth::user()->current_team_id)->get();
        return view('system_processes.goals.edit', compact('goal', 'users'));
    }

    public function update(GoalsRequest $request, $id)
    {
        $goal = Goal::findOrFail($id);
        $this->authorize('update', $goal);

        try{
            $goal->update($request->except('responsibility'));
            $goal->users()->sync($request->responsibility);

            $notification = $goal->notification;
            if(!$notification){
                $notification=new Notification();
                $notification->team_id=Auth::user()->current_team_id;
            }
            $notification->message = __('Analiza cilja za ').date('d.m.Y', strtotime($request->deadline));
            $notification->checkTime = $goal->deadline;
            $goal->notification()->save($notification);

            $oldNot = InstantNotification::where('notifiable_id', $goal->id)->where('notifiable_type', 'App\Models\Goal')->get();
            if($oldNot->count()){
                $oldNot[0]->user()->sync($request->responsibility);
            }
            else{
                $not = new GoalInstantNotification($goal);
                $not->save();
                $not->user()->sync($request->responsibility);
            }

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
            InstantNotification::where('notifiable_id', $goal->id)->where('notifiable_type', 'App\Models\Goal')->delete();

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
            InstantNotification::where('notifiable_id', $goal->id)->where('notifiable_type', 'App\Models\Goal')->delete();

            CustomLog::info('Cilj id: "'.$goal->id.'" uklonjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return true;
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja cilja id: "'.$goal->id.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            return false;
        }
    }

    public function export(Request $request)
    {
        if(empty(session('standard'))){
            return redirect('/');
        }
        if($request->year == null){
            $request->year=date('Y');
        }
        return Excel::download(new GoalsExport($request->year), Str::snake(__('Ciljevi')).'_'.session('standard_name').'.xlsx');
    }

    public function print($id)
    {
        $goal = Goal::with('user','standard')->findOrFail($id);
        $this->authorize('view', $goal);
        $goal->level=$goal->levelIs();
        return view('system_processes.goals.print', compact('goal'));

    }


}
