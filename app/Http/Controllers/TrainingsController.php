<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Document;
use App\Models\Training;
use App\Facades\CustomLog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\TrainingsExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\TrainingRequest;
use Illuminate\Support\Facades\Storage;

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
            $trainingPlan = Training::create($request->except(['training']));

            if($request->has('training')){
                foreach($request->training as $block){

                    if(isset($block['file'])){
                        foreach($block['file'] as $file){
                            $file_name=pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME).time();
                            $name= $trainingPlan->name;
                            $newFileName=$file_name.".".$file->getClientOriginalExtension();
                            $path = $file->storeAs(Str::snake($this::getCompanyName())."/training", $newFileName);
                            $document = Document::create([
                                'standard_id' => $trainingPlan->standard_id,
                                'team_id' => $trainingPlan->team_id,
                                'user_id' => $trainingPlan->user_id,
                                'document_name'=> $name,
                                'version' => 1,
                                'file_name' => $newFileName,
                                'doc_category' => 'training'
                                ]);

                            foreach($block['users'] as $user){
                                $document->users()->attach($user, ['training_id' => $trainingPlan->id]);
                            }
                        }
                    }
                    else{
                        foreach($block['users'] as $user){
                            $trainingPlan->users()->attach($user, ['document_id' => null]);
                        }
                    }
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

        $training = Training::with(['usersWithoutDocument', 'user', 'documents.users'])->findOrFail($id);
        $training->docArray = $training->documents->unique();

        $training['company'] = Str::snake(Auth::user()->currentTeam->name);
        return response()->json($training);
    }

    public function edit($id)
    {
        //$trainingPlan = Training::with('documents')->with('users')->findOrFail($id);

        $trainingPlan = Training::with(['usersWithoutDocument', 'user', 'documents.users'])->findOrFail($id);
        $trainingPlan->withoutDoc = $trainingPlan->usersWithoutDocument->unique();
        $trainingPlan->docArray = $trainingPlan->documents->unique();
        $users = User::with('teams')->where('current_team_id', Auth::user()->current_team_id)->get();
        $this->authorize('update', $trainingPlan);
        return view('system_processes.trainings.edit', compact('trainingPlan', 'users'));
    }

    public function update(TrainingRequest $request, $id)
    {
        $trainingPlan = Training::findOrFail($id);
        $this->authorize('update', $trainingPlan);

        if($request->has('training')){
            $oldFiles = array_column($request->training,'oldFile');
        }
        else{
            $oldFiles = [];
        }

        $currentFiles = $trainingPlan->documents->pluck('id');
        $diff = $currentFiles->diff($oldFiles)->unique()->values();
        try{
            DB::transaction(function () use($request, $trainingPlan, $diff) {
                if(!$request->exists('training')){
                    $trainingPlan->usersWithoutDocument()->detach();
                }

                if($request->has('training')){
                    foreach($request->training as $key => $block){

                        if(isset($block['file'])){
                            foreach($block['file'] as $file){
                                $file_name=pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME).time();
                                $name= $trainingPlan->name;
                                $newFileName = $file_name.".".$file->getClientOriginalExtension();
                                $path = $file->storeAs(Str::snake($this::getCompanyName())."/training", $newFileName);
                                $document = Document::create([
                                    'standard_id' => $trainingPlan->standard_id,
                                    'team_id' => $trainingPlan->team_id,
                                    'user_id' => $trainingPlan->user_id,
                                    'document_name'=> $name,
                                    'version' => 1,
                                    'file_name' => $newFileName,
                                    'doc_category' => 'training'
                                    ]);

                                foreach($block['users'] as $user){
                                    if($key == 0){
                                        $trainingPlan->users()->wherePivot('document_id', null)->detach($user);
                                    }
                                    $document->users()->attach($user, ['training_id' => $trainingPlan->id]);
                                }
                            }
                        }

                        if(isset($block['oldFile'])){
                            $document = Document::findOrFail($block['oldFile']);
                            $arr = [];
                            foreach($block['users'] as $user){
                                $arr[$user] = ['training_id' => $trainingPlan->id];
                            }
                            $document->users()->sync($arr);
                        }
                        elseif(isset($block['deletedFile'])){
                                $deletedDocument = Document::findOrFail($block['deletedFile']);
                                foreach($deletedDocument->users as $deletedDocUser){
                                    $deletedDocUser->pivot->document_id = null;
                                    $deletedDocUser->pivot->save();
                                }
                                $path = Str::snake($this::getCompanyName())."/training/".$deletedDocument->file_name;
                                Storage::delete($path);
                                $newDiff = $diff->flip();
                                unset($newDiff[$deletedDocument->id]);
                                $diff = $newDiff->flip();
                                $deletedDocument->forceDelete();
                        }
                        else{
                            if($key != 0){
                                $arr = [];
                                foreach($block['users'] as $user){
                                    $arr[$user] = ['document_id' => null];
                                }
                                $trainingPlan->usersWithoutDocument()->sync($arr);
                            }
                            else{
                                if(!isset($block['file'])){
                                    $arr = [];
                                    foreach($block['users'] as $user){
                                        $arr[$user] = ['document_id' => null];
                                    }
                                    $trainingPlan->usersWithoutDocument()->sync($arr);
                                }
                            }
                        }
                    }
                }

                if(isset($request->newTraining)){
                    foreach($request->newTraining as $block){
                        if(isset($block['file'])){
                            foreach($block['file'] as $file){
                                $file_name=pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME).time();
                                $name= $trainingPlan->name;
                                $newFileName=$file_name.".".$file->getClientOriginalExtension();
                                $path = $file->storeAs(Str::snake($this::getCompanyName())."/training", $newFileName);
                                $document = Document::create([
                                    'standard_id' => $trainingPlan->standard_id,
                                    'team_id' => $trainingPlan->team_id,
                                    'user_id' => $trainingPlan->user_id,
                                    'document_name'=> $name,
                                    'version' => 1,
                                    'file_name' => $newFileName,
                                    'doc_category' => 'training'
                                    ]);

                                foreach($block['users'] as $user){
                                    $document->users()->attach($user, ['training_id' => $trainingPlan->id]);
                                }
                            }
                        }
                        else{
                            foreach($block['users'] as $user){
                                $trainingPlan->users()->attach($user, ['document_id' => null]);
                            }
                        }
                    }
                }

                //Brisanje celog bloka
                foreach($diff as $d){
                    $doc = Document::findorFail($d);
                    $doc->users()->sync([]);
                    $path = Str::snake($this::getCompanyName())."/training/".$doc->file_name;
                    Storage::delete($path);
                    $doc->forceDelete();
                }

            });

            $trainingPlan->update($request->except('training','newTraining'));
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

        $docs = $trainingPlan->documents;

        try{
            Training::destroy($id);

            foreach($docs as $doc){
                $path = Str::snake($this::getCompanyName())."/training/".$doc->file_name;
                Storage::delete($path);
                $doc->forceDelete();
            }
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

        $docs = $trainingPlan->documents;

        try{
            Training::destroy($id);

            foreach($docs as $doc){
                $path = Str::snake($this::getCompanyName())."/training/".$doc->file_name;
                Storage::delete($path);
                $doc->forceDelete();
            }

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
