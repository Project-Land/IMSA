<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Document;
use App\Models\Training;
use App\Facades\CustomLog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TrainingRequest;
use Illuminate\Support\Facades\Storage;

class TrainingsController extends Controller
{

    public function index()
    {
        if(session('standard') == null){
            return redirect('/')->with('status', array('secondary', 'Izaberite standard!'));
        }

        $trainingPlans = Training::where([
                ['standard_id', session('standard')],
                ['year', date('Y')],
                ['team_id',Auth::user()->current_team_id]
            ])->get();
        return view('system_processes.trainings.index', compact('trainingPlans'));
    }

    public function getData(Request $request) {
        $trainingPlans = Training::where([
                ['standard_id', session('standard')],
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
       
        
        try{ dd($request->file('file'));
            
            $path = $request->file->storeAs($this::getCompanyName())."/policy", $request->file('file')->getClientOriginalName().time().$request->file('file')->getClientOriginalExtension());
            $trainingPlan = Training::create($request->except('status'));
            $upload_path = Str::snake($this::getCompanyName())."/Trainings";
            $document = Document::create([
                'training_id'=>$trainingPlan->id,
                'standard_id'=>$trainingPlan->standard_id,
                'team_id'=>$trainingPlan->team_id,
                'user_id'=>$trainingPlan->user_id,
                'document_name'=>$trainingPlan->name,
                'version'=>1,
                'file_name'=>'training' 
                ]);
            Storage::putFileAs($upload_path, $request->file, $request->file_name);
            CustomLog::info('Obuka "'.$trainingPlan->name.'" kreirana, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', 'Obuka je uspešno sačuvana!'));
        }catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja obuke, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }
        return redirect('/trainings');
    }

    public function show($id)
    {
        if(!request()->expectsJson()){
            abort(404);
        }
        $training = Training::findOrFail($id);
        return response()->json($training);
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
            CustomLog::info('Obuka "'.$trainingPlan->name.'" izmenjena, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', 'Obuka je uspešno izmenjena!'));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene obuke "'.$trainingPlan->name.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }
        return redirect('/trainings');
    }

    public function destroy($id)
    {
        $trainingPlan = Training::findOrFail($id);
        $this->authorize('delete', $trainingPlan);
        try{
            Training::destroy($id);
            CustomLog::info('Obuka "'.$trainingPlan->name.'" uklonjena, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', 'Obuka je uspešno uklonjena!'));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja obuke "'.$trainingPlan->name.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }
    }

    public function deleteApi($id)
    {
        $trainingPlan = Training::findOrFail($id);
        $this->authorize('delete', $trainingPlan);
        try{
            Training::destroy($id);
            CustomLog::info('Obuka "'.$trainingPlan->name.'" uklonjena, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return true;
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja obuke "'.$trainingPlan->name.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            return false;
        }
    }
}
