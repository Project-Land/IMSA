<?php

namespace App\Http\Controllers;

use App\Models\Stakeholder;
use Illuminate\Support\Facades\Auth;
use App\Facades\CustomLog;
use App\Http\Requests\StakeholdersRequest;
use Exception;

class StakeholdersController extends Controller
{

    public function index()
    {
        if($this::getStandard() == null){
            return redirect('/');
        }

        $stakeholders = Stakeholder::where([
                ['standard_id', $this::getStandard()],
                ['team_id',Auth::user()->current_team_id]
            ])->get();

        return view('system_processes.stakeholders.index', compact('stakeholders'));
    }

    public function create()
    {
        $this->authorize('create', Stakeholder::class);
        return view('system_processes.stakeholders.create');
    }

    public function store(StakeholdersRequest $request)
    {
        $this->authorize('create', Stakeholder::class);
        
        try{
            $stakeholder = Stakeholder::create($request->all());
            CustomLog::info('Zainteresovana strana "'.$request->name.'" kreirana. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Zainteresovana strana je uspešno sačuvana!');
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja zainteresovane strane. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s').' Greška- '.$e->getMessage(), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Došlo je do greške, pokušajte ponovo!');
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
            CustomLog::info('Zainteresovana strana "'.$stakeholder->name.'" izmenjena. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Zainteresovana strana je uspešno izmenjena!');
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene zainteresovane strane '.$stakeholder->name.' . Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s').' Greška- '.$e->getMessage(), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/stakeholders');
    }

    public function destroy($id)
    {
        $this->authorize('delete', Stakeholder::find($id));
        $stakeholder = Stakeholder::findOrFail($id);
        
        try{
            Stakeholder::destroy($id);
            CustomLog::info('Zainteresovana strana "'.$stakeholder->name.'" uklonjena. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), \Auth::user()->currentTeam->name);
            return back()->with('status', 'Zainteresovana strana je uspešno uklonjena');
        } catch (Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja zainteresovane strane '.$stakeholder->name.' . Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s').' Greška- '.$e->getMessage(), \Auth::user()->currentTeam->name);
            return back()->with('status', 'Došlo je do greške! Pokušajte ponovo.');
        }
    }
}
