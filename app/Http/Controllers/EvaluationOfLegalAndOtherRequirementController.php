<?php

namespace App\Http\Controllers;

use Exception;
use App\Facades\CustomLog;
use Illuminate\Http\Request;
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

        try{
            $requirement = EvaluationOfLegalAndOtherRequirement::create($request->all());

            CustomLog::info('Vrednovanje zakonskih i drugih zahteva id- "'.$requirement->id.'" je kreirano, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Vrednovanje zakonskih i drugih zahteva je uspešno sačuvano!');
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja Vrednovanja zakonskih i drugih zahteva, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('warning', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/evaluation-of-requirements');
    }

    public function edit($id)
    {
        $requirement = EvaluationOfLegalAndOtherRequirement::findOrFail($id);
        $this->authorize('update', $requirement);
        return view('system_processes.evaluation_of_requirement.edit', ['requirement' => $requirement]);
    }

    public function update(EvaluationOfRequirementRequest $request,$id){
        $requirement = EvaluationOfLegalAndOtherRequirement::findOrFail($id);
        $this->authorize('update', $requirement);

        try{
            $requirement->update($request->all());
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
