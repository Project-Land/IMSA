<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RiskManagement;
use Illuminate\Support\Facades\Auth;
use App\Facades\CustomLog;
use App\Http\Requests\UpdateRiskManagementPlanRequest;
use Exception;

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
        try{
            $risk->save();
            CustomLog::info('Rizik "'.$risk->description.'" dodat. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
            $request->session()->flash('status', 'Dokument je uspešno sačuvan!');
        }catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja rizika. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s').' Greška- '.$e->getMessage(), 'Firma-'.\Auth::user()->current_team_id);
            $request->session()->flash('status', 'Došlo je do greške, pokušajte ponovo!');
        }
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
        try{
        $risk->save();
        CustomLog::info('Rizik "'.$risk->description.'" izmenjen. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
        $request->session()->flash('status', 'Dokument je uspešno izmenjen!');
        }catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene rizika id-'.$risk->id.'. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s').' Greška- '.$e->getMessage(), 'Firma-'.\Auth::user()->current_team_id);
            $request->session()->flash('status', 'Došlo je do greške, pokušajte ponovo!');
        }
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
        $risk = RiskManagment::findOrFail($id);
        if(RiskManagement::destroy($id)){
            CustomLog::info('Rizik "'.$risk->description.'" uklonjen. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
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

    public function updatePlan(UpdateRiskManagementPlanRequest $request, $id)
    {
        $risk = RiskManagement::findOrFail($id);
        $risk->cause = $request->cause;
        $risk->risk_lowering_measure = $request->risk_lowering_measure;
        $risk->responsibility = $request->responsibility;
        $risk->deadline = date('Y-m-d', strtotime($request->deadline));
        $risk->status = $request->status;
        try{
            $risk->save();
            CustomLog::info('Plan za postupanje sa rizikom "'.$risk->description.'" izmenjen. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
            $request->session()->flash('status', 'Plan je uspešno izmenjen!');
        }catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene plana za postupanje sa rizikom id-'.$risk->id.'. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s').' Greška- '.$e->getMessage(), 'Firma-'.\Auth::user()->current_team_id);
            $request->session()->flash('status', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/risk-management');
    }
}
