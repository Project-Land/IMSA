<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Facades\CustomLog;
use Illuminate\Http\Request;
use App\Models\CorrectiveMeasure;
use Illuminate\Support\Facades\Auth;
use App\Policies\EvaluationOfRequirementPolicy;
use App\Models\EvaluationOfLegalAndOtherRequirement;
use App\Http\Requests\EvaluationOfRequirementRequest;
use App\Policies\EvaluationOfLegalAndOtherRequirementPolicy;

class EvaluationOfLegalAndOtherRequirementController extends Controller
{
    public function index(){
        
        if(session('standard') == null){
            return redirect('/')->with('status', 'Izaberite standard!');
        }

        $EvaluationOfLegalAndOtherRequirement = EvaluationOfLegalAndOtherRequirement::where([
                ['standard_id', session('standard')],
                ['team_id', Auth::user()->current_team_id]
            ])->with(['standard','correctiveMeasures'])->get();


        return view('system_processes.evaluation_of_requirement.index', ['EvaluationOfLegalAndOtherRequirement' => $EvaluationOfLegalAndOtherRequirement]);
    }

    public function create(){
        $this->authorize('create', EvaluationOfLegalAndOtherRequirement::class);
        return view('system_processes.evaluation_of_requirement.create');
    }
   
    public function store(EvaluationOfRequirementRequest $request)
    {
        $this->authorize('create', EvaluationOfLegalAndOtherRequirement::class);

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
           
            $requirement = EvaluationOfLegalAndOtherRequirement::create([
            'requirement_level'=>$request->requirement_level,
            'document_name'=>$request->document_name,
            'compliance'=>$request->compliance,
            'note'=>$request->note,
            'standard_id'=>$request->standard_id,
            'user_id'=>$request->user_id,
            'team_id'=>$request->team_id]);
           
            if( isset($correctiveMeasureData['noncompliance_description'])){
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
                    'name' => "KKM ".Carbon::now()->year." / ".$counter,
                    'noncompliance_cause_date' => Carbon::now(),
                    'measure_date' => Carbon::now(),
                    'measure_approval_date' => $correctiveMeasureData['measure_approval'][$inc] == '1' ? Carbon::now() : null
                ]);

                $requirement->correctiveMeasures()->save($correctiveMeasure);
                
            }
        }

            CustomLog::info('Vrednovanje zakonskih i drugih zahteva id- "'.$requirement->id.'" je kreirano, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Vrednovanje zakonskih i drugih zahteva je uspešno sačuvano!');
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja Vrednovanja zakonskih i drugih zahteva, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').',Linija '.$e->getLine().' Greška '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('warning', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/evaluation-of-requirements');
    }

    public function edit($id)
    {
        $requirement = EvaluationOfLegalAndOtherRequirement::with('correctiveMeasures')->findOrFail($id);
        $this->authorize('update', $requirement);
        return view('system_processes.evaluation_of_requirement.edit', ['requirement' => $requirement]);
    }

    public function update(EvaluationOfRequirementRequest $request,$id){
        $requirement = EvaluationOfLegalAndOtherRequirement::findOrFail($id);
        $this->authorize('update', $requirement);
        $correctiveMeasureData=$request->validate([
            'noncompliance_source' => 'required',
            'noncompliance_description' => 'required',
            'noncompliance_cause' => 'required',
            'measure' => 'required',
            'measure_approval' => 'required',
            'measure_approval_reason' => 'nullable',
            'measure_status' => 'required',
            'measure_effective' => 'nullable'
        ], [
            'noncompliance_source.required' => 'Izvor informacije o neusaglašenostima nije izabran',
            'noncompliance_description.required' => 'Opis neusaglašenosti nije popunjen',
            'noncompliance_cause.required' => 'Uzrok neusaglašenosti nije popunjen',
            'measure.required' => 'Mera za otklanjanje neusaglašenosti nije popunjena',
            'measure_approval.required' => 'Razlog neodobravanja mere nije popunjen',
            'measure_status.required' => 'Polje mera efektivna nije popunjeno',
        ]);

        try{
            $requirement->update([
                'requirement_level'=>$request->requirement_level,
                'document_name'=>$request->document_name,
                'compliance'=>$request->compliance,
                'note'=>$request->note,
               ]);
            if( isset($correctiveMeasureData['noncompliance_description'])){
               
                    $counter = CorrectiveMeasure::whereYear('created_at', '=', Carbon::now()->year)
                    ->where([
                        ['standard_id', session('standard')],
                        ['team_id', Auth::user()->current_team_id]
                    ])
                    ->count() + 1;
                    $correctiveMeasure= $requirement->correctiveMeasures[0];
                    $correctiveMeasure->update([
                        'noncompliance_source' => $correctiveMeasureData['noncompliance_source'],
                        'noncompliance_description' => $correctiveMeasureData['noncompliance_description'],
                        'noncompliance_cause' => $correctiveMeasureData['noncompliance_cause'],
                        'measure' => $correctiveMeasureData['measure'],
                        'measure_approval_reason' => $correctiveMeasureData['measure_approval_reason'],
                        'measure_approval' => $correctiveMeasureData['measure_approval'],
                        'measure_status' => $correctiveMeasureData['measure_status'],
                        'measure_effective' => $correctiveMeasureData['measure_effective'],
                        'team_id' => Auth::user()->current_team_id,
                        'user_id' => Auth::user()->id,
                        'standard_id' => session('standard'),
                        'sector_id' => 1,
                        'name' => "KKM ".Carbon::now()->year." / ".$counter,
                        'noncompliance_cause_date' => Carbon::now(),
                        'measure_date' => Carbon::now(),
                        'measure_approval_date' => $correctiveMeasureData['measure_approval'] == '1' ? Carbon::now() : null
                    ]);
    
                    $requirement->correctiveMeasures()->save($correctiveMeasure);
                    
                
            }
            CustomLog::info('Vrednovanje zakonskih i drugih zahteva id- "'.$requirement->id.'" izmenjeno, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Vrednovanje zakonskih i drugih zahteva je uspešno izmenjeno!');
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene Vrednovanja zakonskih i drugih zahteva id- "'.$requirement->id.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('warning', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/evaluation-of-requirements');
    }

    public function destroy($id)
    {
        $requirement = EvaluationOfLegalAndOtherRequirement::findOrFail($id);
        $this->authorize('delete', $requirement);

        try{
           
            EvaluationOfLegalAndOtherRequirement::destroy($id);
            CustomLog::info('Vrednovanje zakonskih i drugih zahteva id- "'.$requirement->id.'" uklonjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', 'Vrednovanje zakonskih i drugih zahteva je uspešno uklonjeno');
        } catch (Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja Vrednovanja zakonskih i drugih zahteva "'.$requirement->id.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('warning', 'Došlo je do greške! Pokušajte ponovo.');
        }
    }

}
