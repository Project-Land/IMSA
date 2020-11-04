<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Standard;
use App\Facades\CustomLog;
use Illuminate\Http\Request;
use App\Models\Inconsistency;
use App\Models\InternalCheck;
use App\Models\Recommendation;
use App\Models\CorrectiveMeasure;
use Illuminate\Support\Facades\DB;
use App\Models\InternalCheckReport;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class InternalCheckReportController extends Controller
{
    public function index()
    {   
       //
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
        ]);

        $recommendationData = $request->validate([
            'newInputRecommendation1' => 'string',
            'newInputRecommendation2' => 'string',
            'newInputRecommendation3' => 'string',
            'newInputRecommendation4' => 'string',
            'newInputRecommendation5' => 'string'          
        ]);

        $InconsistencyData = $request->validate([
            'newInput1' => 'string',
            'newInput2' => 'string',
            'newInput3' => 'string',
            'newInput4' => 'string',  
            'newInput5' => 'string',    
        ]);
        
        $correctiveMeasureData=$request->validate([
            'noncompliance_source.*' => 'required',
            'noncompliance_description.*' => 'required',
            'noncompliance_cause.*' => 'required',
            'measure.*' => 'required', 
            'measure_approval.*' => 'required',
            'measure_approval_reason.*' => 'nullable',
            'measure_status.*' => 'required',
            'measure_effective.*' => 'nullable'
        ],[
            'noncompliance_source.*.required' => 'Izvor informacije o neusaglašenostima nije izabran',
            'noncompliance_description.*.required' => 'Opis neusaglašenosti nije popunjen',
            'noncompliance_cause.*.required' => 'Uzrok neusaglašenosti nije popunjen',
            'measure.*.required' => 'Mera za otklanjanje neusaglašenosti nije popunjena', 
            'measure_approval.*.required' => 'Razlog neodobravanja mere nije popunjen',
            'measure_status.*.required' => 'Polje mera efektivna nije popunjeno',
           
           
        ]
    );

        try{
            DB::transaction(function () use ($request, $validatedData, $recommendationData, $InconsistencyData, $correctiveMeasureData){ 
                $count = 1;
                $standard = Standard::where('name', $request->standard)->get()[0];
                $report = InternalCheckReport::create($validatedData);

                foreach( $InconsistencyData as $inc){
                    if($inc === "")continue;
                    $inconsistency = new Inconsistency();
                    $inconsistency->description = $inc;
                    $report->inconsistencies()->save($inconsistency);

                    $counter = CorrectiveMeasure::whereYear('created_at', '=', Carbon::now()->year)
                    ->where([
                        ['standard_id', session('standard')],
                        ['team_id', \Auth::user()->current_team_id]
                    ])
                    ->count() + 1;

                    $correctiveMeasure=CorrectiveMeasure::create([
                        'noncompliance_source'=> $correctiveMeasureData['noncompliance_source'][$count],
                        'noncompliance_description'=> $correctiveMeasureData['noncompliance_description'][$count],
                        'noncompliance_cause'=>$correctiveMeasureData['noncompliance_cause'][$count],
                        'measure'=> $correctiveMeasureData['measure'][$count],
                        'measure_approval_reason'=> $correctiveMeasureData['measure_approval_reason'][$count],
                        'measure_approval'=>$correctiveMeasureData['measure_approval'][$count],
                        'measure_status'=>$correctiveMeasureData['measure_status'][$count],
                        'measure_effective'=>$correctiveMeasureData['measure_effective'][$count],
                        'team_id' => \Auth::user()->current_team_id,
                        'user_id' =>\Auth::user()->id,
                        'standard_id' => session('standard'),
                        'sector_id' => 1,
                        'name' => "KKM ".Carbon::now()->year." / ".$counter,
                        'noncompliance_cause_date' => Carbon::now(),
                        'measure_date' => Carbon::now(),
                        'measure_approval_date' => $correctiveMeasureData['measure_approval'][$count] == '1' ? Carbon::now() : null
                    ]);
    
                    $correctiveMeasure->standard()->associate($standard);
                    $count++;
                    $inconsistency->correctiveMeasure()->save($correctiveMeasure);

                }
                foreach( $recommendationData as $rec){
                    if($rec === "")continue;
                    $recommendation = new Recommendation();
                    $recommendation->description=$rec;
                    $report->recommendations()->save($recommendation);
                }

                $report->refresh();
                $internalCheck = InternalCheck::findOrFail($request->internal_check_id);
                $report->internalCheck()->save($internalCheck);
                CustomLog::info('Izveštaj za internu proveru id-"'.$report->id.'" je kreiran, '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
                $request->session()->flash('status', 'Izveštaj za godišnji plan je uspešno kreiran!');
            });
        }catch(Exception $e){
            $request->session()->flash('status','Došlo je do greške, pokušajte ponovo');
            CustomLog::warning('Neuspeli pokušaj kreiranja izveštaja interne provere, '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
        }
        return redirect('/internal-check');
    }

    public function show($id)
    {
       $report = InternalCheckReport::where('id', $id)->with('recommendations', 'inconsistencies')->get();
       echo $report;
    }

    public function edit($id)
    {
        $internal_check_report = InternalCheckReport::where('id', $id)->with('internalCheck', 'recommendations', 'inconsistencies')->get();
        $internal_check_report = $internal_check_report[0];
        $this->authorize('update', $internal_check_report);
        return view('system_processes.internal_check_report.edit', ['internalCheckReport' => $internal_check_report]);
    }

    public function update(Request $request, $id)
    {  

        $correctiveMeasureData=$request->validate([
            'noncompliance_source.*' => 'string|required',
            'noncompliance_description.*' => 'string|required',
            'noncompliance_cause.*' => 'string|required',
            'measure.*' => 'string|required', 
            'measure_approval.*' => 'string|required',
            'measure_approval_reason.*' => 'string|nullable',
            'measure_status.*' => 'string|required',
            'measure_effective.*' => 'string|nullable'
            
        ]);
      
        $validatedData = $request->validate([
            'specification' => 'required|min:3',
        ]);

        $inconsistenciesData = $request->validate([
            'inconsistencies.*' => 'string|required|min:3',  
        ]);

        $recommendationsData = $request->validate([  
            'recommendations.*' => 'string|required|min:3',  
        ]);
        
        $newInconsistenciesData = $request->validate([
            'newInput1' => 'string|min:3',
            'newInput2' => 'string|min:3',
            'newInput3' => 'string|min:3',
            'newInput4' => 'string|min:3',
        ]);

        $newRecommendationsData = $request->validate([
            'newInputRecommendation1' => 'string|min:3',
            'newInputRecommendation2' => 'string|min:3',
            'newInputRecommendation3' => 'string|min:3',
            'newInputRecommendation4' => 'string|min:3',   
        ]);

        try{
            DB::transaction(function () use ($request, $id, $correctiveMeasureData, $validatedData, $inconsistenciesData, $recommendationsData, $newInconsistenciesData, $newRecommendationsData){ 
                $count = 1;
                $standard = Standard::where('name', $request->standard)->get()[0];
                $internal_check_report = InternalCheckReport::findOrfail($id);
                $internal_check_report->update($validatedData);
                
                if(isset($inconsistenciesData['inconsistencies'])){
                    $incs = $internal_check_report->inconsistencies;
                    foreach($incs as $i){
                        if(!in_array($i->id, array_keys($inconsistenciesData['inconsistencies']))){
                            $i->delete();
                        }
                    }
                    foreach($inconsistenciesData['inconsistencies'] as $k => $v){
                        $inc = Inconsistency::findOrFail($k);
                        $inc->description = $v;
                        $internal_check_report->inconsistencies()->save($inc);
                    }
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

                foreach($newInconsistenciesData as $v){
            
                    $inc = new Inconsistency();
                    $inc->description = $v;
                    $internal_check_report->inconsistencies()->save($inc);
                    $inc->refresh();

                    $counter = CorrectiveMeasure::whereYear('created_at', '=', Carbon::now()->year)
                        ->where([
                            ['standard_id', session('standard')],
                            ['team_id', \Auth::user()->current_team_id]
                        ])
                        ->count() + 1;

                    $correctiveMeasure=CorrectiveMeasure::create([
                        'noncompliance_source'=> $correctiveMeasureData['noncompliance_source'][$count],
                        'noncompliance_description'=> $correctiveMeasureData['noncompliance_description'][$count],
                        'noncompliance_cause'=>$correctiveMeasureData['noncompliance_cause'][$count],
                        'measure'=> $correctiveMeasureData['measure'][$count],
                        'measure_approval_reason'=> $correctiveMeasureData['measure_approval_reason'][$count],
                        'measure_approval'=>$correctiveMeasureData['measure_approval'][$count],
                        'measure_status'=>$correctiveMeasureData['measure_status'][$count],
                        'measure_effective'=>$correctiveMeasureData['measure_effective'][$count],
                        'team_id' => \Auth::user()->current_team_id,
                        'user_id' => \Auth::user()->id,
                        'sector_id' => 1,
                        'standard_id' => session('standard'),
                        'name' => "KKM ".Carbon::now()->year." / ".$counter,
                        'noncompliance_cause_date' => Carbon::now(),
                        'measure_date' => Carbon::now(),
                        'measure_approval_date' => $correctiveMeasureData['measure_approval'][$count] == '1' ? Carbon::now() : null
                    ]);

                    $correctiveMeasure->standard()->associate($standard);
                    $count++;
                    $inc->correctiveMeasure()->save($correctiveMeasure);
                }

                foreach($newRecommendationsData as $v){
                    $rec = new Recommendation();
                    $rec->description = $v;
                    $internal_check_report->recommendations()->save($rec);          
                }

                CustomLog::info('Izveštaj za internu proveru "'.$internal_check_report->specification.'" je izmenjen, '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
                $request->session()->flash('status', 'Izveštaj za godišnji plan je uspešno izmenjen!');
            });

        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene Izveštaja interne provere "'.$internal_check_report->specification.'", '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Došlo je do greske, pokušajte ponovo');
            return redirect('/internal-check');
            exit();
        } 
        
        return redirect('/internal-check');
    }
    
    public function destroy($id)
    {
        $internal_check_report = InternalCheckReport::find($id);
        $this->authorize('delete', $internal_check_report);

        try{
            InternalCheckReport::destroy($id);
            CustomLog::info('Izveštaj za internu proveru "'.$internal_check_report->specification.'" je obrisan, '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
            return back()->with('status', 'Izveštaj za godišnji plan interne provere je uspešno uklonjen');
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja izveštaja za internu proveru "'.$internal_check_report->specification.'", '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
            return back()->with('status', 'Došlo je do greške, pokušajte ponovo');
        }
    }
}
