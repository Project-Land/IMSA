<?php

namespace App\Http\Controllers;

use App\Models\RiskManagement;
use Illuminate\Support\Facades\Auth;
use App\Facades\CustomLog;
use App\Http\Requests\UpdateRiskManagementPlanRequest;
use App\Http\Requests\RiskManagementRequest;
use Exception;

class RiskManagementController extends Controller
{

    public function index()
    {
        if($this::getStandard() == null){
            return redirect('/')->with('status', 'Izaberite standard!');
        }
        
        $riskManagements = RiskManagement::where([
                ['standard_id', $this::getStandard()],
                ['team_id',Auth::user()->current_team_id]
            ])->get();
        return view('system_processes.risk_management.index', compact('riskManagements'));
    }

    public function create()
    {
        $this->authorize('create', RiskManagement::class);
        return view('system_processes.risk_management.create');
    }

    public function store(RiskManagementRequest $request)
    {
        $this->authorize('create', RiskManagement::class);
        try{
            $risk = RiskManagement::create($request->all());
            CustomLog::info('Rizik "'.$risk->description.'" dodat, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Dokument je uspešno sačuvan!');
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja rizika, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s').' Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
            $request->session()->flash('warning', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/risk-management');
    }

    public function show($id)
    {
        $risk = RiskManagement::findOrFail($id);
        return response()->json($risk);
    }

    public function edit($id)
    {
        $this->authorize('update', RiskManagement::find($id));
        $risk = RiskManagement::findOrFail($id);
        return view('system_processes.risk_management.edit', compact('risk'));
    }

    public function update(RiskManagementRequest $request, $id)
    {
        $this->authorize('update', RiskManagement::find($id));
        $risk = RiskManagement::findOrFail($id);

        try{
            $risk->update($request->all());
            CustomLog::info('Rizik "'.$risk->description.'" izmenjen, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Dokument je uspešno izmenjen!');
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene rizika '.$risk->description.', '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s').' Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
            $request->session()->flash('warning', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/risk-management');
    }

    public function destroy($id)
    {
        $this->authorize('delete', RiskManagement::find($id));
        $risk = RiskManagement::findOrFail($id);

        try{
            RiskManagement::destroy($id);
            CustomLog::info('Rizik "'.$risk->description.'" uklonjen, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
            return back()->with('status', 'Rizik / plan je uspešno uklonjen');
        }
        catch (Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja rizika "'.$risk->description.'", '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s').' Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
            return back()->with('warning', 'Došlo je do greške! Pokušajte ponovo.');
        }
    }

    public function editPlan($id)
    {
        $risk = RiskManagement::findOrFail($id);
        return view('system_processes.risk_management.edit-plan', compact('risk'));
    }

    public function updatePlan(UpdateRiskManagementPlanRequest $request, $id)
    {
        $this->authorize('update', RiskManagement::find($id));
        $risk = RiskManagement::findOrFail($id);

        try{
            $risk->update($request->all());
            CustomLog::info('Plan za postupanje sa rizikom "'.$risk->description.'" izmenjen, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Plan je uspešno izmenjen!');
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene plana za postupanje sa rizikom '.$risk->description.', '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s').' Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
            $request->session()->flash('warning', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/risk-management');
    }
}
