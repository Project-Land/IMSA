<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Standard;
use App\Facades\CustomLog;
use Illuminate\Http\Request;
use App\Models\InternalCheck;
use App\Models\Recommendation;
use App\Models\CorrectiveMeasure;
use Illuminate\Support\Facades\DB;
use App\Models\InternalCheckReport;
use Illuminate\Support\Facades\Auth;

class InternalCheckReportController extends Controller
{
    public function index()
    {
       abort(404);
    }

    public function createReport($id)
    {
        $internalCheck = InternalCheck::findOrFail($id);
        $this->authorize('update', $internalCheck);

        return view('system_processes.internal_check_report.create', ['internalCheck' => $internalCheck]);
    }


    public function store(Request $request)
    {
        $this->authorize('create', InternalCheck::class);

        $validatedData = $request->validate([
            'specification' => 'required'
        ], ['specification.required' => 'Specifikacija nije popunjena']);

        $recInputs = []; $recMsg = [];

        for($i = 1; $i <= 10; $i++){
            $recInputs['newInputRecommendation'.$i] = 'string|min:1';
            $recMsg["newInputRecommendation{$i}.min"] = 'Preporuka nije popunjena (popunite ili obrišite polje)';
        }

        $recommendationData = $request->validate($recInputs, $recMsg);

        $correctiveMeasureData=$request->validate([
            'noncompliance_source.*' => 'required',
            'noncompliance_description.*' => 'required',
            'noncompliance_cause.*' => 'required',
            'measure.*' => 'required',
            'measure_approval.*' => 'required',
            'measure_approval_reason.*' => 'nullable',
            'measure_status.*' => 'required',
            'measure_effective.*' => 'nullable'
        ], [
            'noncompliance_source.*.required' => 'Izvor informacije o neusaglašenostima nije izabran',
            'noncompliance_description.*.required' => 'Opis neusaglašenosti nije popunjen',
            'noncompliance_cause.*.required' => 'Uzrok neusaglašenosti nije popunjen',
            'measure.*.required' => 'Mera za otklanjanje neusaglašenosti nije popunjena',
            'measure_approval.*.required' => 'Razlog neodobravanja mere nije popunjen',
            'measure_status.*.required' => 'Polje mera efektivna nije popunjeno',
        ]);

        try{
            DB::transaction(function () use ($request, $validatedData, $recommendationData, $correctiveMeasureData){
                $count = 1;
                $standard = Standard::where('name', $request->standard)->get()[0];
                $report = InternalCheckReport::create($validatedData);
                if(isset($correctiveMeasureData['noncompliance_description'])){
                foreach( $correctiveMeasureData['noncompliance_description'] as $inc => $v){
                    $counter = CorrectiveMeasure::whereYear('created_at', '=', Carbon::now()->year)
                    ->where([
                        ['standard_id', session('standard')],
                        ['team_id', Auth::user()->current_team_id]
                    ])
                    ->count() + 1;

                    $correctiveMeasure=CorrectiveMeasure::create([
                        'noncompliance_source' => $correctiveMeasureData['noncompliance_source'][$inc],
                        'noncompliance_description' => $correctiveMeasureData['noncompliance_description'][$inc],
                        'noncompliance_cause' => $correctiveMeasureData['noncompliance_cause'][$inc],
                        'measure' => $correctiveMeasureData['measure'][$inc],
                        'measure_approval_reason' => $correctiveMeasureData['measure_approval_reason'][$inc],
                        'measure_approval' => $correctiveMeasureData['measure_approval'][$inc],
                        'measure_status' => $correctiveMeasureData['measure_status'][$inc],
                        'measure_effective' => $correctiveMeasureData['measure_effective'][$inc],
                        'team_id' => Auth::user()->current_team_id,
                        'user_id' => Auth::user()->id,
                        'standard_id' => session('standard'),
                        'sector_id' => 1,
                        'name' => __("KKM")." ".Carbon::now()->year." / ".$counter,
                        'noncompliance_cause_date' => Carbon::now(),
                        //'internal_check_report_id' => $report->id,
                        'measure_date' => Carbon::now(),
                        'measure_approval_date' => $correctiveMeasureData['measure_approval'][$inc] == '1' ? Carbon::now() : null
                    ]);

                    $correctiveMeasure->standard()->associate($standard);
                    $report->correctiveMeasures()->save($correctiveMeasure);
                    $count++;
                }
            }

                foreach( $recommendationData as $rec){
                    if($rec === "")continue;
                    $recommendation = new Recommendation();
                    $recommendation->description = $rec;
                    $report->recommendations()->save($recommendation);
                }

                $report->refresh();
                $internalCheck = InternalCheck::findOrFail($request->internal_check_id);
                $report->internalCheck()->save($internalCheck);
                CustomLog::info('Izveštaj za internu proveru id: "'.$report->id.'" je kreiran, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
                $request->session()->flash('status', array('info', 'Izveštaj interne provere je uspešno kreiran!'));
            });
        } catch(Exception $e){
            $request->session()->flash('status', array('info', 'Došlo je do greške, pokušajte ponovo'));
            CustomLog::warning('Neuspeli pokušaj kreiranja izveštaja interne provere, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
        }
        return redirect('/internal-check');
    }

    public function show($id)
    {
        if(!request()->expectsJson()){
            abort(404);
        }
        $report = InternalCheckReport::where('id', $id)->with('recommendations', 'correctiveMeasures')->get();
        echo $report;
    }

    public function edit($id)
    {
        $internal_check_report = InternalCheckReport::with('internalCheck', 'recommendations', 'correctiveMeasures')->findOrFail($id);
        $this->authorize('update', $internal_check_report);
        return view('system_processes.internal_check_report.edit', ['internalCheckReport' => $internal_check_report]);
    }

    public function update(Request $request, $id)
    {

        $validatedData = $request->validate([
            'specification' => 'required',
        ], ['specification.required' => 'Specifikacija nije popunjena']);

        $inconsistenciesData = $request->validate([
            'inconsistencies.*' => 'required',
        ], ['inconsistencies.*.required' => 'Neusaglašenost nije popunjena (popunite ili obrišite)']);

        $recommendationsData = $request->validate([
            'recommendations.*' => 'required',
        ], ['recommendations.*.required' => 'Preporuka nije popunjena (popunite ili obrišite polje)']);

        $recInputs=[]; $recMsg=[];
        for($i = 1; $i <= 10; $i++){
            $recInputs['newInputRecommendation'.$i] = 'string|min:1';
            $recMsg["newInputRecommendation{$i}.min"] = 'Preporuka nije popunjena (popunite ili obrišite polje)';
        }

        $newRecommendationsData = $request->validate($recInputs, $recMsg);

        $internal_check_report = InternalCheckReport::findOrfail($id);

        try{
            DB::transaction(function () use ($request, $id, $validatedData, $inconsistenciesData, $recommendationsData, $newRecommendationsData, $internal_check_report){
                $internal_check_report->update($validatedData);

                if(isset($inconsistenciesData['inconsistencies'])){
                    $incs = $internal_check_report->correctiveMeasures;
                    foreach($incs as $i){
                        if(!in_array($i->id, array_keys($inconsistenciesData['inconsistencies']))){
                            $i->delete();
                        }
                    }
                    foreach($inconsistenciesData['inconsistencies'] as $k => $v){
                        $inc = CorrectiveMeasure::findOrFail($k);
                        $inc->noncompliance_description = $v;
                        $internal_check_report->correctiveMeasures()->save($inc);
                    }
                } else{
                    $internal_check_report->correctiveMeasures()->delete();
                }

                if(isset($recommendationsData['recommendations'])){
                    $recs = $internal_check_report->recommendations;
                    foreach($recs as $r){
                        if(!in_array($r->id, array_keys($recommendationsData['recommendations']))){
                            $r->delete();
                        }
                    }
                    foreach($recommendationsData['recommendations'] as $k => $v){
                        $rec = Recommendation::findOrFail($k);
                        $rec->description = $v;
                        $internal_check_report->recommendations()->save($rec);
                    }
                }

                foreach($newRecommendationsData as $v){
                    $rec = new Recommendation();
                    $rec->description = $v;
                    $internal_check_report->recommendations()->save($rec);
                }

                CustomLog::info('Izveštaj za internu proveru id: "'.$internal_check_report->id.'" je izmenjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
                $request->session()->flash('status', array('info', 'Izveštaj interne provere je uspešno izmenjen!'));
            });

        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene izveštaja interne provere id: "'.$internal_check_report->id.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', 'Došlo je do greske, pokušajte ponovo'));
            return redirect('/internal-check-report/'. $internal_check_report->id.'/edit');
            exit();
        }

        return redirect('/internal-check-report/'. $internal_check_report->id.'/edit');
    }

    public function destroy($id)
    {
        $internal_check_report = InternalCheckReport::find($id);
        $this->authorize('delete', $internal_check_report);

        try{
            $internal_check_report->correctiveMeasures()->delete();
            InternalCheckReport::destroy($id);
            CustomLog::info('Izveštaj za internu proveru "'.$internal_check_report->id.'" je obrisan, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', 'Izveštaj interne provere je uspešno uklonjen'));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja izveštaja interne provere id: "'.$internal_check_report->id.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', 'Došlo je do greške, pokušajte ponovo'));
        }
    }
}
