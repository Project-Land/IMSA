<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Facades\CustomLog;

class GoalsController extends Controller
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
        $goals = Goal::where([
                ['standard_id', $standardId],
                ['team_id', Auth::user()->current_team_id]
            ])->get();
        return view('system_processes.goals.index', ['goals' => $goals]);
    }

    public function getData(Request $request) {
        $standardId = $this::getStandard();
        if($standardId == null){
            return redirect('/');
        }
        $goal = Goal::where([
                ['standard_id', $standardId],
                ['year', $request->data['year']],
                ['team_id', Auth::user()->current_team_id]
            ])->get();
        return response()->json($goal);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('system_processes.goals.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $goal = new Goal();

        $messages = array(
            'responsibility.required' => 'Unesite odgovornost',
            'responsibility.max' => 'Polje može sadržati najviše 190 karaktera',
            'goal.required' => 'Unesite cilj',
            'goal.max' => 'Polje može sadržati najviše 190 karaktera',
            'deadline.required' => 'Unesite rok za realizaciju',
            'kpi.required' => 'Unesite KPI',
            'kpi.max' => 'Polje može sadržati najviše 190 karaktera',
            'resources.required' => 'Unesite resurse',
            'resources.max' => 'Polje može sadržati najviše 190 karaktera',
            'activities.required' => 'Unesite aktivnosti'
        );

        $validatedData = $request->validate([
            'responsibility' => 'required|max:190',
            'goal' => 'required|max:190',
            'deadline' => 'required',
            'kpi' => 'required|max:190',
            'resources' => 'required|max:190',
            'activities' => 'required'
        ], $messages);

        $goal->standard_id = $this::getStandard();
        $goal->year = $request->year;
        $goal->responsibility = $request->resources;
        $goal->goal = $request->goal;
        $goal->deadline = date('Y-m-d', strtotime($request->deadline));
        $goal->kpi = $request->kpi;
        $goal->resources = $request->resources;
        $goal->activities = $request->activities;
        $goal->analysis = $request->analysis != null ? $request->analysis : null;

        $goal->team_id = Auth::user()->current_team_id;
        $goal->user_id = Auth::user()->id;

        $goal->save();
        CustomLog::info('Cilj "'.$goal->goal.'" kreiran. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
        $request->session()->flash('status', 'Cilj je uspešno sačuvan!');
        return redirect('/goals');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $goal = Goal::findOrFail($id);
        return response()->json($goal);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $goal = Goal::findOrFail($id);
        return view('system_processes.goals.edit', ['goal' => $goal]);
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
        $goal = Goal::findOrFail($id);

        $messages = array(
            'responsibility.required' => 'Unesite odgovornost',
            'responsibility.max' => 'Polje može sadržati najviše 190 karaktera',
            'goal.required' => 'Unesite cilj',
            'goal.max' => 'Polje može sadržati najviše 190 karaktera',
            'deadline.required' => 'Unesite rok za realizaciju',
            'kpi.required' => 'Unesite KPI',
            'kpi.max' => 'Polje može sadržati najviše 190 karaktera',
            'resources.required' => 'Unesite resurse',
            'resources.max' => 'Polje može sadržati najviše 190 karaktera',
            'activities.required' => 'Unesite aktivnosti'
        );

        $validatedData = $request->validate([
            'responsibility' => 'required|max:190',
            'goal' => 'required|max:190',
            'deadline' => 'required',
            'kpi' => 'required|max:190',
            'resources' => 'required|max:190',
            'activities' => 'required'
        ], $messages);

        $goal->standard_id = $this::getStandard();
        $goal->year = $request->year;
        $goal->responsibility = $request->resources;
        $goal->goal = $request->goal;
        $goal->deadline = date('Y-m-d', strtotime($request->deadline));
        $goal->kpi = $request->kpi;
        $goal->resources = $request->resources;
        $goal->activities = $request->activities;
        $goal->analysis = $request->analysis != null ? $request->analysis : null;

        $goal->save();
        CustomLog::info('Cilj "'.$goal->goal.'" izmenjen. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
        $request->session()->flash('status', 'Cilj je uspešno izmenjen!');
        return redirect('/goals');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $goal = Goal::findOrFail($id);
        if(Goal::destroy($id)){
            CustomLog::info('Cilj "'.$goal->goal.'" uklonjen. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
            return back()->with('status', 'Cilj je uspešno uklonjen');
        }else{
            return back()->with('status', 'Došlo je do greške! Pokušajte ponovo.');
        }
    }

    public function deleteApi($id)
    {
        $goal = Goal::findOrFail($id);
        Goal::destroy($id);
        CustomLog::info('Cilj "'.$goal->name.'" uklonjen. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
        return true;
    }
}
