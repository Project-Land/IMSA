<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Facades\CustomLog;
use Illuminate\Support\Str;
use App\Models\CorrectiveMeasure;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\EvaluationOfLegalAndOtherRequirement;
use App\Http\Requests\EvaluationOfRequirementRequest;
use App\Exports\EvaluationOfLegalAndOtherRequirementsExport;

class EvaluationOfLegalAndOtherRequirementController extends Controller
{
    public function index(){

        if(session('standard') == null || (! (session('standard_name') == "45001" || session('standard_name') == "14001"))){
            return redirect('/');
        }

        $EvaluationOfLegalAndOtherRequirement = EvaluationOfLegalAndOtherRequirement::where([
                ['standard_id', session('standard')],
                ['team_id', Auth::user()->current_team_id]
            ])->with(['standard', 'correctiveMeasures', 'user'])->get();

        return view('system_processes.evaluation_of_requirement.index', ['EvaluationOfLegalAndOtherRequirement' => $EvaluationOfLegalAndOtherRequirement]);
    }

    public function create(){
        if(session('standard') == null || (! (session('standard_name') == "45001" || session('standard_name') == "14001"))){
            return redirect('/');
        }
        $this->authorize('create', EvaluationOfLegalAndOtherRequirement::class);
        return view('system_processes.evaluation_of_requirement.create');
    }

    public function store(EvaluationOfRequirementRequest $request)
    {
        $this->authorize('create', EvaluationOfLegalAndOtherRequirement::class);

        $correctiveMeasureData = $request->validate([
            'noncompliance_source.*' => 'required',
            'noncompliance_description.*' => 'required',
            'noncompliance_cause.*' => 'required',
            'measure.*' => 'required',
            'measure_approval.*' => 'required',
            'measure_approval_reason.*' => 'nullable',
            'measure_status.*' => 'required',
            'measure_effective.*' => 'nullable'
        ], [
            'noncompliance_source.*.required' => __('Izvor informacije o neusaglašenostima nije izabran'),
            'noncompliance_description.*.required' => __('Opis neusaglašenosti nije popunjen'),
            'noncompliance_cause.*.required' => __('Uzrok neusaglašenosti nije popunjen'),
            'measure.*.required' => __('Mera za otklanjanje neusaglašenosti nije popunjena'),
            'measure_approval.*.required' => __('Razlog neodobravanja mere nije popunjen'),
            'measure_status.*.required' => __('Polje mera efektivna nije popunjeno'),
        ]);

        try{
            $requirement = EvaluationOfLegalAndOtherRequirement::create([
            'requirement_level' => $request->requirement_level,
            'document_name' => $request->document_name,
            'compliance' => $request->compliance,
            'note' => $request->note,
            'standard_id' => $request->standard_id,
            'user_id' => $request->user_id,
            'team_id' => $request->team_id]);

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
                        'name' => "EMS KKM ".Carbon::now()->year." / ".$counter,
                        'noncompliance_cause_date' => Carbon::now(),
                        'measure_date' => Carbon::now(),
                        'measure_approval_date' => $correctiveMeasureData['measure_approval'][$inc] == '1' ? Carbon::now() : null
                    ]);

                $requirement->correctiveMeasures()->save($correctiveMeasure);
                }
            }

            CustomLog::info('Vrednovanje zakonskih i drugih zahteva id- "'.$requirement->id.'" je kreirano, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', __('Vrednovanje zakonskih i drugih zahteva je uspešno sačuvano!')));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja Vrednovanja zakonskih i drugih zahteva, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').',Linija '.$e->getLine().' Greška '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', __('Došlo je do greške, pokušajte ponovo!')));
        }
        return redirect('/evaluation-of-requirements');
    }

    public function edit($id)
    {
        $requirement = EvaluationOfLegalAndOtherRequirement::with('correctiveMeasures')->findOrFail($id);
        $this->authorize('update', $requirement);
        return view('system_processes.evaluation_of_requirement.edit', ['requirement' => $requirement]);
    }

    public function update(EvaluationOfRequirementRequest $request, $id){
        $requirement = EvaluationOfLegalAndOtherRequirement::findOrFail($id);
        $this->authorize('update', $requirement);
        $correctiveMeasureData = [];

        if($request->has('noncompliance_source')){
            $correctiveMeasureData = $request->validate([
                'noncompliance_source' => 'required',
                'noncompliance_description' => 'required',
                'noncompliance_cause' => 'required',
                'measure' => 'required',
                'measure_approval' => 'required',
                'measure_approval_reason' => 'nullable',
                'measure_status' => 'required',
                'measure_effective' => 'nullable'
            ], [
                'noncompliance_source.required' => __('Izvor informacije o neusaglašenostima nije izabran'),
                'noncompliance_description.required' => __('Opis neusaglašenosti nije popunjen'),
                'noncompliance_cause.required' => __('Uzrok neusaglašenosti nije popunjen'),
                'measure.required' => __('Mera za otklanjanje neusaglašenosti nije popunjena'),
                'measure_approval.required' => __('Razlog neodobravanja mere nije popunjen'),
                'measure_status.required' => __('Polje mera efektivna nije popunjeno'),
            ]);
        }

        try{
            $requirement->update([
                'requirement_level' => $request->requirement_level,
                'document_name' => $request->document_name,
                'compliance' => $request->compliance,
                'note' => $request->note,
                'updated_at' => Carbon::now()
            ]);

            if( isset($correctiveMeasureData['noncompliance_description'])){

                    $counter = CorrectiveMeasure::whereYear('created_at', '=', Carbon::now()->year)
                    ->where([
                        ['standard_id', session('standard')],
                        ['team_id', Auth::user()->current_team_id]
                    ])
                    ->count() + 1;
                    if($requirement->correctiveMeasures()->count()){

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
                        ]);
                        if( !$correctiveMeasure->measure_status)$correctiveMeasure->measure_effective=null;
                    }else{
                            $correctiveMeasure=CorrectiveMeasure::create([
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
                            'name' => "EMS KKM ".Carbon::now()->year." / ".$counter,
                            'noncompliance_cause_date' => Carbon::now(),
                            'measure_date' => Carbon::now(),
                            'measure_approval_date' => $correctiveMeasureData['measure_approval'] == '1' ? Carbon::now() : null
                        ]);
                    }

                    $requirement->correctiveMeasures()->save($correctiveMeasure);

            }else{
               // $requirement->correctiveMeasures()->delete();
            }
            CustomLog::info('Vrednovanje zakonskih i drugih zahteva id: "'.$requirement->id.'" izmenjeno, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', __('Vrednovanje zakonskih i drugih zahteva je uspešno izmenjeno!')));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene Vrednovanja zakonskih i drugih zahteva id: "'.$requirement->id.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').',Linija:'.$e->getLine().' Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', __('Došlo je do greške, pokušajte ponovo!')));
        }
        return redirect('/evaluation-of-requirements');
    }

    public function destroy($id)
    {
        $requirement = EvaluationOfLegalAndOtherRequirement::findOrFail($id);
        $this->authorize('delete', $requirement);
        try{
            $requirement->correctiveMeasures()->delete();
            EvaluationOfLegalAndOtherRequirement::destroy($id);
            CustomLog::info('Vrednovanje zakonskih i drugih zahteva id: "'.$requirement->id.'" uklonjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', __('Vrednovanje zakonskih i drugih zahteva je uspešno uklonjeno')));
        } catch (Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja Vrednovanja zakonskih i drugih zahteva id: "'.$requirement->id.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', __('Došlo je do greške! Pokušajte ponovo.')));
        }
    }

    public function export()
    {
        if(empty(session('standard'))){
            return redirect('/');
        }
        return Excel::download(new  EvaluationOfLegalAndOtherRequirementsExport(), Str::snake(__('Vrednovanje zakonskih i drugih zahteva')).'_'.session('standard_name').'.xlsx');
    }
    public function print($id)
    {
        $requirement = EvaluationOfLegalAndOtherRequirement::with('user','standard','correctiveMeasures')->findOrFail($id);
        $this->authorize('view', $requirement);
        return view('system_processes.evaluation_of_requirement.print', compact('requirement'));
    }

}
