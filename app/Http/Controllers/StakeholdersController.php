<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Stakeholder;
use Illuminate\Support\Facades\Auth;
use App\Facades\CustomLog;
use App\Http\Requests\StakeholdersRequest;
use App\Exports\StakeholdersExport;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class StakeholdersController extends Controller
{

    public function index()
    {
        if(empty(session('standard'))){
            return redirect('/')->with('status', array('secondary', __('Izaberite standard!')));
        }

        $stakeholders = Stakeholder::where([
                ['standard_id', session('standard')],
                ['team_id', Auth::user()->current_team_id]
            ])->with(['user'])->get();

        return view('system_processes.stakeholders.index', compact('stakeholders'));
    }

    public function create()
    {
        if(empty(session('standard'))){
            return redirect('/');
        }
        $this->authorize('create', Stakeholder::class);
        return view('system_processes.stakeholders.create');
    }

    public function store(StakeholdersRequest $request)
    {
        $this->authorize('create', Stakeholder::class);

        try{
            $stakeholder = Stakeholder::create($request->all());
            CustomLog::info('Zainteresovana strana "'.$stakeholder->name.'" kreirana, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', __('Zainteresovana strana je uspešno sačuvana!')));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja zainteresovane strane, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', __('Došlo je do greške, pokušajte ponovo!')));
        }
        return redirect('/stakeholders');
    }

    public function show($id)
    {
        $stakeholder = Stakeholder::findOrFail($id);
        abort(404);
    }

    public function edit($id)
    {
        $stakeholder = Stakeholder::findOrFail($id);
        $this->authorize('update', $stakeholder);
        return view('system_processes.stakeholders.edit', compact('stakeholder'));
    }

    public function update(StakeholdersRequest $request, $id)
    {
        $stakeholder = Stakeholder::findOrFail($id);
        $this->authorize('update', $stakeholder);

        try{
            $stakeholder->update($request->all());
            CustomLog::info('Zainteresovana strana "'.$stakeholder->name.'" izmenjena, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', __('Zainteresovana strana je uspešno izmenjena!')));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene zainteresovane strane '.$stakeholder->name.', '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', __('Došlo je do greške, pokušajte ponovo!')));
        }
        return redirect('/stakeholders');
    }

    public function destroy($id)
    {
        $this->authorize('delete', Stakeholder::find($id));
        $stakeholder = Stakeholder::findOrFail($id);

        try{
            Stakeholder::destroy($id);
            CustomLog::info('Zainteresovana strana "'.$stakeholder->name.'" uklonjena, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', __('Zainteresovana strana je uspešno uklonjena')));
        } catch (Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja zainteresovane strane '.$stakeholder->name.', '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', __('Došlo je do greške! Pokušajte ponovo.')));
        }
    }

    public function export()
    {
        if(empty(session('standard'))){
            return redirect('/');
        }

        return Excel::download(new StakeholdersExport, Str::snake(__('Zainteresovane strane')).'.xlsx');
    }
}
