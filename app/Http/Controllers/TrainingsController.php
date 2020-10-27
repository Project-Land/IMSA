<?php

namespace App\Http\Controllers;

use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Facades\CustomLog;

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
        return response()->json($trainingPlans);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('system_processes.trainings.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $trainingPlan = new Training();

        $messages = array(
            'name.required' => 'Unesite naziv obuke',
            'name.max' => 'Naziv može sadržati najviše 190 karaktera',
            'desription.required' => 'Unesite opis obuke',
            'num_of_employees.required' => 'Unesite broj zaposlenih',
            'place.required' => 'Unesite mesto obuke',
            'place.max' => 'Polje može sadržati najviše 190 karaktera',
            'resources.required' => 'Unesite potrebne resurse',
            'training_date.required' => 'Unesite datum i vreme obuke'
        );

        $validatedData = $request->validate([
            'name' => 'required|max:190',
            'description' => 'required',
            'num_of_employees' => 'required',
            'description' => 'required',
            'place' => 'required|max:190',
            'resources' => 'required',
            'training_date' => 'required'
        ], $messages);

        $trainingPlan->standard_id = $this::getStandard();
        $trainingPlan->name = $request->name;
        $trainingPlan->description = $request->description;
        $trainingPlan->year = $request->year;
        $trainingPlan->type = $request->type;
        $trainingPlan->num_of_employees = $request->num_of_employees;
        $trainingPlan->training_date = date('Y-m-d H:i:s', strtotime($request->training_date));
        $trainingPlan->place = $request->place;
        $trainingPlan->resources = $request->resources;
        $trainingPlan->final_num_of_employees = $request->final_num_of_employees != null ? $request->final_num_of_employees : null;
        $trainingPlan->rating = $request->rating != null ? $request->rating : null;

        $trainingPlan->user_id = Auth::user()->id;
        $trainingPlan->team_id = Auth::user()->current_team_id;

        $trainingPlan->save();
        CustomLog::info('Godišnji plan obuke "'.$trainingPlan->name.'" kreiran. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);

        $request->session()->flash('status', 'Godišnji plan obuka je uspešno sačuvan!');
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
        return view('system_processes.trainings.edit', compact('trainingPlan'));
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
        $trainingPlan = Training::findOrFail($id);

        $messages = array(
            'name.required' => 'Unesite naziv obuke',
            'name.max' => 'Naziv može sadržati najviše 190 karaktera',
            'desription.required' => 'Unesite opis obuke',
            'num_of_employees.required' => 'Unesite broj zaposlenih',
            'place.required' => 'Unesite mesto obuke',
            'place.max' => 'Polje može sadržati najviše 190 karaktera',
            'resources.required' => 'Unesite potrebne resurse',
            'training_date.required' => 'Unesite datum i vreme obuke'
        );

        $validatedData = $request->validate([
            'name' => 'required|max:190',
            'description' => 'required',
            'num_of_employees' => 'required',
            'description' => 'required',
            'place' => 'required|max:190',
            'resources' => 'required',
            'training_date' => 'required'
        ], $messages);

        $trainingPlan->name = $request->name;
        $trainingPlan->description = $request->description;
        $trainingPlan->year = $request->year;
        $trainingPlan->type = $request->type;
        $trainingPlan->num_of_employees = $request->num_of_employees;
        $trainingPlan->training_date = date('Y-m-d H:i', strtotime($request->training_date));
        $trainingPlan->place = $request->place;
        $trainingPlan->resources = $request->resources;
        $trainingPlan->final_num_of_employees = $request->final_num_of_employees != null ? $request->final_num_of_employees : null;
        $trainingPlan->rating = $request->rating != null ? $request->rating : null;

        $trainingPlan->save();
        CustomLog::info('Godišnji plan obuke "'.$trainingPlan->name.'" izmenjen. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
        $request->session()->flash('status', 'Godišnji plan obuka je uspešno izmenjen!');
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
        Training::destroy($id);
        CustomLog::info('Godišnji plan obuke uklonjen "'.$trainingPlan->name.'" kreiran. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
        return back()->with('status', 'Godišnji plan obuke je uspešno uklonjen');
    }

    public function deleteApi($id)
    {
        $trainingPlan = Training::findOrFail($id);
        Training::destroy($id);
        CustomLog::info('Godišnji plan obuke uklonjen "'.$trainingPlan->name.'" kreiran. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
        return true;
    }
}
