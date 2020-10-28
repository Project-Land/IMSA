<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sector;
use App\Models\Standard;
use Illuminate\Http\Request;
use App\Models\CorrectiveMeasure;
use Illuminate\Support\Facades\Auth;
use App\Facades\CustomLog;
use Exception;

class CorrectiveMeasuresController extends Controller
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

        $measures = CorrectiveMeasure::where('team_id',Auth::user()->current_team_id)->with('inconsistency')->get();
        return view('system_processes.corrective_measures.index', compact('measures'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', CorrectiveMeasure::class);

        $standards = Standard::all();
        $sectors = Sector::where('team_id', Auth::user()->current_team_id)->get();
        return view('system_processes.corrective_measures.create', compact('standards', 'sectors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', CorrectiveMeasure::class);

        $correctiveMeasure = new CorrectiveMeasure();
       
        $messages = array(
            'standard.required' => 'Izaberite standard',
            'sector.required' => 'Izaberite organizacionu celinu',
            'noncompliance_source.required' => 'Unesite izvor informacije o neusaglašenosti',
            'noncompliance_description.required' => 'Unesite opis neusaglašenosti',
            'noncompliance_cause.required' => 'Unesite uzrok neusaglašenosti',
            'measure.required' => 'Unesite meru za otklanjanje neusaglašenosti',
        );

        $validatedData = $request->validate([
            'standard' => 'required',
            'sector' => 'required',
            'noncompliance_source' => 'required',
            'noncompliance_description' => 'required',
            'noncompliance_cause' => 'required',
            'measure' => 'required',
        ], $messages);

        $counter = CorrectiveMeasure::whereYear('created_at', '=', Carbon::now()->year)->where('standard_id', $request->standard)->count() + 1;

        $correctiveMeasure->name = "KKM ".Carbon::now()->year." / ".$counter;

        $correctiveMeasure->standard_id = $request->standard;
        $correctiveMeasure->sector_id = $request->sector;

        $correctiveMeasure->noncompliance_source = $request->noncompliance_source;
        $correctiveMeasure->noncompliance_description = $request->noncompliance_description;
        $correctiveMeasure->noncompliance_cause = $request->noncompliance_cause;
        $correctiveMeasure->noncompliance_cause_date = Carbon::now();
        $correctiveMeasure->measure = $request->measure;
        $correctiveMeasure->measure_date = Carbon::now();
        $correctiveMeasure->measure_approval = $request->measure_approval;
        $correctiveMeasure->measure_status = $request->measure_status;

        $correctiveMeasure->measure_approval_reason = $request->measure_approval_reason != '' ? $request->measure_approval_reason : null;
        $correctiveMeasure->measure_effective = $request->measure_effective != null ? $request->measure_effective : null;
        $correctiveMeasure->measure_approval_date = $request->measure_approval == '1' ? Carbon::now() : null;

        $correctiveMeasure->user_id = Auth::user()->id;
        $correctiveMeasure->team_id = Auth::user()->current_team_id;
        try{
            $correctiveMeasure->save();
            CustomLog::info('Neusaglašenost "'.$correctiveMeasure->name.'" kreirana. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
            $request->session()->flash('status', 'Korektivna mera je uspešno sačuvana!');
        }catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja neusaglašenosti. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s').' Greška- '.$e->getMessage(), 'Firma-'.\Auth::user()->current_team_id);
            $request->session()->flash('status', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/corrective-measures');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $corrective_measure = CorrectiveMeasure::with('standard')->with('sector')->findOrFail($id);
        return response()->json($corrective_measure);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $corrective_measure = CorrectiveMeasure::findOrFail($id);
        $this->authorize('update', $corrective_measure);
        $standards = Standard::all();
        $sectors = Sector::where('team_id', Auth::user()->current_team_id)->get();
        return view('system_processes.corrective_measures.edit', compact('corrective_measure', 'standards', 'sectors'));
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
        $correctiveMeasure = CorrectiveMeasure::findOrFail($id);

        $this->authorize('update', $correctiveMeasure);

        $messages = array(
            'standard.required' => 'Izaberite standard',
            'sector.required' => 'Izaberite organizacionu celinu',
            'noncompliance_source.required' => 'Unesite izvor informacije o neusaglašenosti',
            'noncompliance_description.required' => 'Unesite opis neusaglašenosti',
            'noncompliance_cause.required' => 'Unesite uzrok neusaglašenosti',
            'measure.required' => 'Unesite meru za otklanjanje neusaglašenosti',
        );

        $validatedData = $request->validate([
            'standard' => 'required',
            'sector' => 'required',
            'noncompliance_source' => 'required',
            'noncompliance_description' => 'required',
            'noncompliance_cause' => 'required',
            'measure' => 'required',
        ], $messages);

        $correctiveMeasure->standard_id = $request->standard;
        $correctiveMeasure->sector_id = $request->sector;

        $correctiveMeasure->noncompliance_source = $request->noncompliance_source;
        $correctiveMeasure->noncompliance_description = $request->noncompliance_description;
        $correctiveMeasure->noncompliance_cause = $request->noncompliance_cause;

        $correctiveMeasure->measure = $request->measure;
        $correctiveMeasure->measure_approval = $request->measure_approval;
        $correctiveMeasure->measure_status = $request->measure_status;

        $correctiveMeasure->measure_approval_reason = $request->measure_approval_reason != '' ? $request->measure_approval_reason : null;
        $correctiveMeasure->measure_effective = $request->measure_effective != null ? $request->measure_effective : null;
        $correctiveMeasure->measure_approval_date = $request->measure_approval == '1' ? Carbon::now() : null;

        if($request->measure_status == 0){
            $correctiveMeasure->measure_effective = null;
        }
        try{
        $correctiveMeasure->save();
        CustomLog::info('Neusaglašenost "'.$correctiveMeasure->name.'" izmenjena. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
        $request->session()->flash('status', 'Korektivna mera je uspešno izmenjena!');
        }catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene neusaglašenosti. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s').' Greška- '.$e->getMessage(), 'Firma-'.\Auth::user()->current_team_id);
            $request->session()->flash('status', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/corrective-measures');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete', CorrectiveMeasure::find($id));
        $correctiveMeasure = CorrectiveMeasure::findOrFail($id);
        if(CorrectiveMeasure::destroy($id)){
            CustomLog::info('Neusaglašenost "'.$correctiveMeasure->name.'" uklonjena. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
            return back()->with('status', 'Neusaglašenost / korektivna mera je uspešno obrisana');
        }else{
            return back()->with('status', 'Došlo je do greške! Pokušajte ponovo.');
        }
    }
}
