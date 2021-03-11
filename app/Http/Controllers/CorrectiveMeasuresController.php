<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Team;
use App\Models\Sector;
use App\Facades\CustomLog;
use App\Models\Notification;
use App\Models\CorrectiveMeasure;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CorrectiveMeasuresRequest;

class CorrectiveMeasuresController extends Controller
{

    public function index()
    {
        if(request()->has('standard') && request()->has('standard_name')){
            session(['standard' => request()->get('standard')]);
            session(['standard_name' => request()->get('standard_name')]);
        }
        if(session('standard') == null){
            return redirect('/')->with('status', array('secondary', 'Izaberite standard!'));
        }

        $measures = CorrectiveMeasure::where([
                ['standard_id', session('standard')],
                ['team_id', Auth::user()->current_team_id]
            ])->with(['standard'])->get();
        return view('system_processes.corrective_measures.index', compact('measures'));
    }

    public function create()
    {
        if(session('standard') == null){
            return redirect('/');
        }
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
            $notification = Notification::create([
                'message'=>__('Rok za realizaciju korektivne mere ').date('d.m.Y', strtotime($correctiveMeasure->deadline_date)),
                'team_id'=>Auth::user()->current_team_id,
                'checkTime' => $correctiveMeasure->deadline_date
            ]);
        $correctiveMeasure->notification()->save($notification);
            CustomLog::info('Neusaglašenost / korektivna mera "'.$correctiveMeasure->name.'" kreirana, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', 'Neusaglašenost / korektivna mera je uspešno sačuvana'));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja neusaglašenosti / korektivne mere, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška- '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }
        return redirect('/corrective-measures');
    }

    public function storeApi(CorrectiveMeasuresRequest $request)
    {
        $this->authorize('create', CorrectiveMeasure::class);
        try{
            $correctiveMeasure = CorrectiveMeasure::create($request->all());
            $notification = Notification::create([
                'message'=>__('Rok za realizaciju korektivne mere ').date('d.m.Y', strtotime($correctiveMeasure->deadline_date)),
                'team_id'=>Auth::user()->current_team_id,
                'checkTime' => $correctiveMeasure->deadline_date
            ]);
            $correctiveMeasure->notification()->save($notification);
            CustomLog::info('Neusaglašenost / korektivna mera "'.$correctiveMeasure->name.'" kreirana, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', 'Neusaglašenost / korektivna mera je uspešno sačuvana'));
        } catch(Exception $e){
            $request->session()->flash('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
            CustomLog::warning('Neuspeli pokušaj kreiranja neusaglašenosti / korektivne mere, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška- '.$e->getMessage(), Auth::user()->currentTeam->name);
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
            $notification = $correctiveMeasure->notification;
            if(!$notification){
                $notification=new Notification();
                $notification->team_id=Auth::user()->current_team_id;
            }
            $notification->message = __('Rok za realizaciju korektivne mere ').date('d.m.Y', strtotime($request->deadline_date));
            $notification->checkTime = $correctiveMeasure->deadline_date;
            $correctiveMeasure->notification()->save($notification);
            CustomLog::info('Neusaglašenost / korektivna mera "'.$correctiveMeasure->name.'" izmenjena, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', 'Neusaglašenost / korektivna mera je uspešno izmenjena'));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene neusaglašenosti / korektivne mere "'.$correctiveMeasure->name.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška- '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }
        return redirect('/corrective-measures');
    }

    public function destroy($id)
    {
        $this->authorize('delete', CorrectiveMeasure::find($id));
        $correctiveMeasure = CorrectiveMeasure::findOrFail($id);

        try{
            $correctiveMeasure->notification()->delete();
            CorrectiveMeasure::destroy($id);
            CustomLog::info('Neusaglašenost / korektivna mera "'.$correctiveMeasure->name.'" uklonjena, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', 'Neusaglašenost / korektivna mera je uspešno obrisana'));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja neusaglašenosti / korektivne mere "'.$correctiveMeasure->name.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška- '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }
    }
}
