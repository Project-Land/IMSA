<?php

namespace App\Http\Controllers;

use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Facades\CustomLog;
use App\Http\Requests\TrainingRequest;
use Exception;

class TrainingsController extends Controller
{

    public function index()
    {
        $standardId = session('standard');
        if($standardId == null){
            return redirect('/')->with('status', 'Izaberite standard!');
        }

        $trainingPlans = Training::where([
                ['standard_id', $standardId],
                ['year', date('Y')],
                ['team_id',Auth::user()->current_team_id]
            ])->get();
        return view('system_processes.trainings.index', compact('trainingPlans'));
    }

    public function getData(Request $request) {
        $standardId = session('standard');
        
        $trainingPlans = Training::where([
                ['standard_id', $standardId],
                ['year', $request->data['year']]
            ])->get();

        $isAdmin = Auth::user()->allTeams()->first()->membership->role == "admin" || Auth::user()->allTeams()->first()->membership->role == "super-admin" ? true : false;

        if(!$trainingPlans->isEmpty()){
            $trainingPlans = $trainingPlans->map(function($item, $key) use($isAdmin){
                $item->isAdmin = $isAdmin;
                return $item;
            });
        }

        return response()->json($trainingPlans);
    }

    public function create()
    {
        $this->authorize('create', Training::class);
        return view('system_processes.trainings.create');
    }

    public function store(TrainingRequest $request)
    {
        $this->authorize('create', Training::class);
        try{
            $trainingPlan = Training::create($request->except('status'));
            CustomLog::info('Obuka "'.$trainingPlan->name.'" kreirana, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Obuka je uspešno sačuvana!');
        }catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja obuke, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s').' Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
            $request->session()->flash('warning', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/trainings');
    }

    public function show($id)
    {
        $training = Training::findOrFail($id);
        abort(404);
    }

    public function edit($id)
    {
        $trainingPlan = Training::findOrFail($id);
        $this->authorize('update', $trainingPlan);
        return view('system_processes.trainings.edit', compact('trainingPlan'));
    }

    public function update(TrainingRequest $request, $id)
    {
        $trainingPlan = Training::findOrFail($id);
        $this->authorize('update', $trainingPlan);
        
        try{
            $trainingPlan->update($request->except('status'));
            CustomLog::info('Obuka "'.$trainingPlan->name.'" izmenjena, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Obuka je uspešno izmenjena!');
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene obuke id-'.$trainingPlan->name.', '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s').' Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
            $request->session()->flash('warning', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/trainings');
    }

    public function destroy($id)
    {
        $trainingPlan = Training::findOrFail($id);
        $this->authorize('delete', $trainingPlan);
        try{
            Training::destroy($id);
            CustomLog::info('Obuka "'.$trainingPlan->name.'" uklonjena, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
            return back()->with('status', 'Obuka je uspešno uklonjena');
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja obuke'.$trainingPlan->name.', '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s').' Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
            return back()->with('warning', 'Došlo je do greške, pokušajte ponovo');
        }
    }

    public function deleteApi($id)
    {
        $trainingPlan = Training::findOrFail($id);
        $this->authorize('delete', $trainingPlan);
        try{
            Training::destroy($id);
            CustomLog::info('Obuka "'.$trainingPlan->name.'" uklonjena, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
            return true;
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja obuke'.$trainingPlan->name.', '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s').' Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
            return false;
        }
    }
}
