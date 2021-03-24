<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Document;
use App\Models\Training;
use App\Facades\CustomLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TrainingRequest;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TrainingsExport;

class TrainingsController extends Controller
{

    public function index()
    {
        if(empty(session('standard'))){
            return redirect('/')->with('status', array('secondary', 'Izaberite standard!'));
        }

        session(['training_year' => date('Y')]);
        $trainingPlans = Training::where([
                ['standard_id', session('standard')],
                ['year', date('Y')],
                ['team_id',Auth::user()->current_team_id]
            ])->orderBy('training_date', 'desc')->get();

        //get document by training and user
        //dd($trainingPlans[0]->users->find(1)->documents()->where('training_id', $trainingPlans[0]->id)->get());

        return view('system_processes.trainings.index', compact('trainingPlans'));
    }

    public function getData(Request $request) {
        if($request->data['year'] == 'all'){
            session(['training_year' => 'all']);
            $trainingPlans = Training::where([
                ['standard_id', session('standard')],
                ['team_id',Auth::user()->current_team_id]
            ])->orderBy('training_date', 'desc')->get();
        }
        else{
            session(['training_year' => $request->data['year']]);
            $trainingPlans = Training::where([
                ['standard_id', session('standard')],
                ['year', $request->data['year']],
                ['team_id',Auth::user()->current_team_id]
            ])->orderBy('training_date', 'desc')->get();
        }

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
        if(empty(session('standard'))){
            return redirect('/');
        }
        $this->authorize('create', Training::class);
        $users = User::with('teams')->where('current_team_id', Auth::user()->current_team_id)->get();
        return view('system_processes.trainings.create', compact('users'));
    }

    public function store(TrainingRequest $request)
    {
        $this->authorize('create', Training::class);
        
        try{
            $trainingPlan = Training::create($request->except(['file','users']));

            if($request->users){
                $trainingPlan->users()->sync($request->users);
            }

            if($request->file('file')){
                foreach($request->file('file') as $file){
                    $file_name=pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME).time();
                    $name= $trainingPlan->name;
                    $trainingPlan->name=$file_name.".".$file->getClientOriginalExtension();
                    $path = $file->storeAs(Str::snake($this::getCompanyName())."/training", $trainingPlan->name);
                    $document = Document::create([
                        'training_id'=>$trainingPlan->id,
                        'standard_id'=>$trainingPlan->standard_id,
                        'team_id'=>$trainingPlan->team_id,
                        'user_id'=>$trainingPlan->user_id,
                        'document_name'=> $name,
                        'version'=>1,
                        'file_name'=>$trainingPlan->name,
                        'doc_category'=>'training'
                        ]);
                }
            }

            CustomLog::info('Obuka "'.$trainingPlan->name.'" kreirana, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', 'Obuka je uspešno sačuvana!'));
        } catch(Exception $e){
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
        $training = Training::with('documents')->with('user')->findOrFail($id);
        $training['company'] = Str::snake(Auth::user()->currentTeam->name);
        return response()->json($training);
    }

    public function edit($id)
    {
        $trainingPlan = Training::with('documents')->with('users')->findOrFail($id);
        $users = User::with('teams')->where('current_team_id', Auth::user()->current_team_id)->get();
        $this->authorize('update', $trainingPlan);
        return view('system_processes.trainings.edit', compact('trainingPlan', 'users'));
    }

    public function update(TrainingRequest $request, $id)
    {
        $trainingPlan = Training::findOrFail($id);
        $this->authorize('update', $trainingPlan);

        try{
            if(!$request->file)$request->file=[];
            foreach($trainingPlan->documents()->pluck('id') as $id){
                if(!in_array($id,$request->file)){
                    $doc=Document::findOrFail($id);
                    $path = Str::snake($this::getCompanyName())."/training/".$doc->file_name;
                    Storage::delete($path);
                    $doc->forceDelete();
                }
            }
            if($request->file('new_file')){
                foreach($request->file('new_file') as $file){
                    $file_name=pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME).time();
                    $name= $trainingPlan->name;
                    $trainingPlan->name=$file_name.".".$file->getClientOriginalExtension();
                    $path = $file->storeAs(Str::snake($this::getCompanyName())."/training", $trainingPlan->name);
                    $document = Document::create([
                        'training_id'=>$trainingPlan->id,
                        'standard_id'=>$trainingPlan->standard_id,
                        'team_id'=>$trainingPlan->team_id,
                        'user_id'=>$trainingPlan->user_id,
                        'document_name'=> $name,
                        'version'=>1,
                        'file_name'=>$trainingPlan->name,
                        'doc_category'=>'training'
                        ]);
                }
            }

            $arrOfUsers = [];
            if($request->users){
                $arrOfUsers = $request->users;
            }

            $trainingPlan->users()->sync($arrOfUsers);

            $trainingPlan->update($request->except('status','file','new_file','users'));
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
            foreach($trainingPlan->documents as $doc){
                $path = Str::snake($this::getCompanyName())."/training/".$doc->file_name;
                Storage::delete($path);
                $doc->forceDelete();
            }
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
            foreach($trainingPlan->documents as $doc){
                $path = Str::snake($this::getCompanyName())."/training/".$doc->file_name;
                Storage::delete($path);
                $doc->forceDelete();
            }
            Training::destroy($id);
            CustomLog::info('Obuka "'.$trainingPlan->name.'" uklonjena, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return true;
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja obuke "'.$trainingPlan->name.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            return false;
        }
    }

    public function export()
    {
        if(empty(session('standard'))){
            return redirect('/');
        }
        return Excel::download(new TrainingsExport, Str::snake(__('Obuke')).'_'.session('standard_name').'.xlsx');
    }
}
