<?php

namespace App\Http\Controllers;

use Exception;
use App\Facades\CustomLog;
use App\Models\EnvironmentalAspect;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\EnvironmentalAspectsRequest;

class EnvironmentalAspectsController extends Controller
{

    public function index()
    {
        if(session('standard') == null || session('standard_name') != 14001){
            return redirect('/')->with('status', array('secondary', __('Izaberite standard!')));
        }

        $environmental_aspects = EnvironmentalAspect::where([
                ['standard_id', session('standard')],
                ['team_id', Auth::user()->current_team_id]
            ])->with(['standard'])->get();
        return view('system_processes.environmental_aspects.index', compact('environmental_aspects'));
    }

    public function create()
    {
        $this->authorize('create', EnvironmentalAspect::class);
        return view('system_processes.environmental_aspects.create');
    }

    public function store(EnvironmentalAspectsRequest $request)
    {
        $this->authorize('create', EnvironmentalAspect::class);

        try{
            $environmental_aspect = EnvironmentalAspect::create($request->all());
            CustomLog::info('Aspekt životne sredine za proces "'.$environmental_aspect->process.'" kreiran, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', __('Aspekt životne sredine je uspešno sačuvan!')));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja aspekta životne sredine, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška- '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', __('Došlo je do greške, pokušajte ponovo!')));
        }
        return redirect('/environmental-aspects');
    }

    public function show($id)
    {
        abort(404);
    }

    public function edit($id)
    {
        $environmental_aspect = EnvironmentalAspect::findOrFail($id);
        $this->authorize('update', $environmental_aspect);
        return view('system_processes.environmental_aspects.edit', compact('environmental_aspect'));
    }

    public function update(EnvironmentalAspectsRequest $request, $id)
    {
        $environmental_aspect = EnvironmentalAspect::findOrFail($id);
        $this->authorize('update', $environmental_aspect);

        try{
            $environmental_aspect->update($request->all());
            CustomLog::info('Aspekt životne sredine za proces "'.$environmental_aspect->process.'" izmenjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', __('Aspekt životne sredine je uspešno izmenjen!')));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene aspekta životne sredine za proces "'.$environmental_aspect->process.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška- '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', __('Došlo je do greške, pokušajte ponovo!')));
        }
        return redirect('/environmental-aspects');
    }

    public function destroy($id)
    {
        $environmental_aspect = EnvironmentalAspect::findOrFail($id);
        $this->authorize('delete', $environmental_aspect);

        try{
            EnvironmentalAspect::destroy($id);
            CustomLog::info('Aspekt životne sredine za proces "'.$environmental_aspect->process.'" uklonjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', __('Aspekt životne sredine je uspešno obrisan')));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja aspekta životne sredine za proces "'.$environmental_aspect->process.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška- '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', __('Došlo je do greške! Pokušajte ponovo')));
        }
    }
}
