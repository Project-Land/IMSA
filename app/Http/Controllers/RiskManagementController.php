<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RiskManagement;
use Illuminate\Support\Facades\Auth;

class RiskManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if($this::getStandard() == null){
            return redirect('/');
        }
        $riskManagements = RiskManagement::where([['standard_id', $this::getStandard()],['team_id',Auth::user()->current_team_id]])->get();
        return view('system_processes.risk_management.index', compact('riskManagements'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('system_processes.risk_management.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $risk = new RiskManagement();

        $messages = array(
            'description.required' => 'Unesite opis'
        );

        $validatedData = $request->validate([
            'description' => 'required'
        ], $messages);

        $risk->standard_id = $this::getStandard();
        $risk->description = $request->description;
        $risk->probability = $request->probability;
        $risk->frequency = $request->frequency;
        $risk->acceptable = $request->acceptable;
        $risk->total = $request->probability * $request->frequency;

        $risk->user_id = Auth::user()->id;
        $risk->team_id = Auth::user()->current_team_id;

        if($risk->acceptable < $risk->total){
            $count = RiskManagement::whereNotNull('measure')->count() + 1;
            $risk->measure = "Plan za postupanje sa rizikom ".$count;
            $risk->measure_created_at = \Carbon\Carbon::now()->toDateTimeString();
        }

        $risk->save();

        $request->session()->flash('status', 'Dokument je uspešno sačuvan!');
        return redirect('/risk-management');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $risk = RiskManagement::findOrFail($id);
        return response()->json($risk);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $risk = RiskManagement::findOrFail($id);
        return view('system_processes.risk_management.edit', compact('risk'));
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
        $risk = RiskManagement::findOrFail($id);

        $messages = array(
            'description.required' => 'Unesite opis'
        );

        $validatedData = $request->validate([
            'description' => 'required'
        ], $messages);

        $risk->standard_id = $this::getStandard();
        $risk->description = $request->description;
        $risk->probability = $request->probability;
        $risk->frequency = $request->frequency;
        $risk->acceptable = $request->acceptable;
        $risk->total = $request->probability * $request->frequency;

        if($risk->acceptable < $risk->total && $risk->measure == null){
            $count = RiskManagement::whereNotNull('measure')->count() + 1;
            $risk->measure = "Plan za postupanje sa rizikom ".$count;
            $risk->measure_created_at = \Carbon\Carbon::now()->toDateTimeString();
        }

        $risk->save();

        $request->session()->flash('status', 'Dokument je uspešno izmenjen!');
        return redirect('/risk-management');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(RiskManagement::destroy($id)){
            return back()->with('status', 'Rizik / plan je uspešno uklonjen');
        }else{
            return back()->with('status', 'Došlo je do greške! Pokušajte ponovo.');
        }
    }

    public function editPlan($id)
    {
        $risk = RiskManagement::findOrFail($id);
        return view('system_processes.risk_management.edit-plan', compact('risk'));
    }

    public function updatePlan(Request $request, $id)
    {
        $risk = RiskManagement::findOrFail($id);

        $messages = array(
            'cause.required' => 'Unesite uzrok',
            'risk_lowering_measure.required' => 'Unesite meru za smanjenje rizika',
            'responsibility.required' => 'Unesite odgovornost',
            'deadline.required' => 'Izaberite rok za realizaciju'
        );

        $validatedData = $request->validate([
            'cause' => 'required',
            'risk_lowering_measure' => 'required',
            'responsibility' => 'required',
            'deadline' => 'required'
        ], $messages);

        $risk->cause = $request->cause;
        $risk->risk_lowering_measure = $request->risk_lowering_measure;
        $risk->responsibility = $request->responsibility;
        $risk->deadline = $request->deadline;
        $risk->status = $request->status;

        $risk->save();

        $request->session()->flash('status', 'Plan je uspešno izmenjen!');
        return redirect('/risk-management');
    }
}
