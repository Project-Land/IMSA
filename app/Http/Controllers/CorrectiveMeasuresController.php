<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Team;
use App\Models\Sector;
use App\Models\Standard;
use App\Facades\CustomLog;
use App\Models\CorrectiveMeasure;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CorrectiveMeasuresRequest;

class CorrectiveMeasuresController extends Controller
{

    public function index()
    {
        $standardId = $this::getStandard();
        if($standardId == null){
            return redirect('/')->with('status', 'Izaberite standard!');
        }

        $measures = CorrectiveMeasure::where('team_id', Auth::user()->current_team_id)->with(['standard'])->get();
        return view('system_processes.corrective_measures.index', compact('measures'));
    }

    public function create()
    {
        $this->authorize('create', CorrectiveMeasure::class);
        $team = Team::findOrFail(Auth::user()->current_team_id);
        $standards = $team->standards->where('id', session('standard'));
        $sectors = Sector::where('team_id', Auth::user()->current_team_id)->get();
        return view('system_processes.corrective_measures.create', compact('standards', 'sectors'));
    }

    public function store(CorrectiveMeasuresRequest $request)
    {
        $this->authorize('create', CorrectiveMeasure::class);
        try{
            $correctiveMeasure = CorrectiveMeasure::create($request->all());
            CustomLog::info('Neusaglašenost "'.$correctiveMeasure->name.'" kreirana, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Korektivna mera je uspešno sačuvana!');
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja neusaglašenosti, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška- '.$e->getMessage(), \Auth::user()->currentTeam->name);
            $request->session()->flash('warning', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/corrective-measures');
    }

    public function storeApi(CorrectiveMeasuresRequest $request)
    {
        $this->authorize('create', CorrectiveMeasure::class);
        try{
            $correctiveMeasure = CorrectiveMeasure::create($request->all());
            CustomLog::info('Neusaglašenost "'.$correctiveMeasure->name.'" kreirana, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Korektivna mera je uspešno kreirana!');
        } catch(Exception $e){
            $request->session()->flash('status', 'Došlo je do greške, pokušajte ponovo!');
            CustomLog::warning('Neuspeli pokušaj kreiranja neusaglašenosti, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška- '.$e->getMessage(), \Auth::user()->currentTeam->name);
        }
        return back();
    }

    public function show($id)
    {
        if(!request()->expectsJson()){
            abort(404);
        }
        $corrective_measure = CorrectiveMeasure::with('standard')->with('sector')->findOrFail($id);
        return response()->json($corrective_measure);
    }

    public function edit($id)
    {
        $corrective_measure = CorrectiveMeasure::findOrFail($id);
        $this->authorize('update', $corrective_measure);

        $team = Team::findOrFail(Auth::user()->current_team_id);
        $standards = $team->standards->where('id', session('standard'));
        $sectors = Sector::where('team_id', Auth::user()->current_team_id)->get();
        return view('system_processes.corrective_measures.edit', compact('corrective_measure', 'standards', 'sectors'));
    }

    public function update(CorrectiveMeasuresRequest $request, $id)
    {
        $correctiveMeasure = CorrectiveMeasure::findOrFail($id);
        $this->authorize('update', $correctiveMeasure);

        try{
            $correctiveMeasure->update($request->except('name'));
            CustomLog::info('Neusaglašenost "'.$correctiveMeasure->name.'" izmenjena, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Korektivna mera je uspešno izmenjena!');
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene neusaglašenosti "'.$correctiveMeasure->name.'", '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška- '.$e->getMessage(), \Auth::user()->currentTeam->name);
            $request->session()->flash('warning', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/corrective-measures');
    }

    public function destroy($id)
    {
        $this->authorize('delete', CorrectiveMeasure::find($id));
        $correctiveMeasure = CorrectiveMeasure::findOrFail($id);

        try{
            CorrectiveMeasure::destroy($id);
            CustomLog::info('Neusaglašenost "'.$correctiveMeasure->name.'" uklonjena, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
            return back()->with('status', 'Neusaglašenost / korektivna mera je uspešno obrisana');
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja neusaglašenosti "'.$correctiveMeasure->name.'", '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška- '.$e->getMessage(), \Auth::user()->currentTeam->name);
            return back()->with('warning', 'Došlo je do greške! Pokušajte ponovo.');
        }
    }
}
