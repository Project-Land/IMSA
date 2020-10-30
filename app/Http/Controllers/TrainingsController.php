<?php

namespace App\Http\Controllers;

use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Facades\CustomLog;
use App\Http\Requests\StoreTrainingRequest;
use App\Http\Requests\UpdateTrainingRequest;
use Exception;

class TrainingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $standardId = $this::getStandard();
        if($standardId == null){
            return redirect('/');
        }

        $trainingPlans = Training::where('standard_id', $standardId)->where([['year', date('Y')],['team_id',Auth::user()->current_team_id]])->get();
        return view('system_processes.trainings.index', compact('trainingPlans'));
    }

    public function getData(Request $request) {
        $standardId = $this::getStandard();
        if($standardId == null){
            return redirect('/');
        }
        $trainingPlans = Training::where('standard_id', $standardId)->where('year', $request->data['year'])->get();

        $isAdmin = Auth::user()->allTeams()->first()->membership->role == "admin" || Auth::user()->allTeams()->first()->membership->role == "super-admin" ? true : false;

        if(!$trainingPlans->isEmpty()){
            $trainingPlans = $trainingPlans->map(function($item, $key) use($isAdmin){
                $item->isAdmin = $isAdmin;
                return $item;
            });
        }

        return response()->json($trainingPlans);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Training::class);
        return view('system_processes.trainings.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTrainingRequest $request)
    {
        $this->authorize('create', Training::class);
        $trainingPlan = new Training();

        $trainingPlan->standard_id = $this::getStandard();
        $trainingPlan->name = $request->name;
        $trainingPlan->description = $request->description;
        $trainingPlan->year = date('Y', strtotime($request->training_date));
        $trainingPlan->type = $request->type;
        $trainingPlan->num_of_employees = $request->num_of_employees;
        $trainingPlan->training_date = date('Y-m-d H:i:s', strtotime($request->training_date));
        $trainingPlan->place = $request->place;
        $trainingPlan->resources = $request->resources;
        $trainingPlan->final_num_of_employees = $request->final_num_of_employees != null ? $request->final_num_of_employees : null;
        $trainingPlan->rating = $request->rating != null ? $request->rating : null;

        $trainingPlan->user_id = Auth::user()->id;
        $trainingPlan->team_id = Auth::user()->current_team_id;
        try{
            $trainingPlan->save();
            CustomLog::info('Godišnji plan obuke "'.$trainingPlan->name.'" kreiran. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
            $request->session()->flash('status', 'Godišnji plan obuka je uspešno sačuvan!');
        }catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja godišnjeg plana obuke. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s').' Greška- '.$e->getMessage(), 'Firma-'.\Auth::user()->current_team_id);
            $request->session()->flash('status', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/trainings');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $training = Training::findOrFail($id);
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $trainingPlan = Training::findOrFail($id);
        $this->authorize('update', $trainingPlan);
        return view('system_processes.trainings.edit', compact('trainingPlan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTrainingRequest $request, $id)
    {
        $trainingPlan = Training::findOrFail($id);
        $this->authorize('update', $trainingPlan);

        $trainingPlan->name = $request->name;
        $trainingPlan->description = $request->description;
        $trainingPlan->year = date('Y', strtotime($request->training_date));
        $trainingPlan->type = $request->type;
        $trainingPlan->num_of_employees = $request->num_of_employees;
        $trainingPlan->training_date = date('Y-m-d H:i', strtotime($request->training_date));
        $trainingPlan->place = $request->place;
        $trainingPlan->resources = $request->resources;
        $trainingPlan->final_num_of_employees = $request->final_num_of_employees != null ? $request->final_num_of_employees : null;
        $trainingPlan->rating = $request->rating != null ? $request->rating : null;
        try{
            $trainingPlan->save();
            CustomLog::info('Godišnji plan obuke "'.$trainingPlan->name.'" izmenjen. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
            $request->session()->flash('status', 'Godišnji plan obuka je uspešno izmenjen!');
        }catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene godišnjeg plana obuke id-'.$trainingPlan->id.'. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s').' Greška- '.$e->getMessage(), 'Firma-'.\Auth::user()->current_team_id);
            $request->session()->flash('status', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/trainings');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $trainingPlan = Training::findOrFail($id);
        $this->authorize('delete', $trainingPlan);
        if(Training::destroy($id)){
            CustomLog::info('Godišnji plan obuke uklonjen "'.$trainingPlan->name.'" kreiran. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
            return back()->with('status', 'Godišnji plan obuke je uspešno uklonjen');
        }
        return back()->with('status', 'Došlo je do greške, pokušajte ponovo');
    }

    public function deleteApi($id)
    {
        $trainingPlan = Training::findOrFail($id);
        $this->authorize('delete', $trainingPlan);
        Training::destroy($id);
        CustomLog::info('Godišnji plan obuke uklonjen "'.$trainingPlan->name.'" kreiran. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
        return true;
    }
}
