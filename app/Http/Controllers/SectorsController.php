<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Sector;
use App\Models\Team;
use App\Facades\CustomLog;
use App\Http\Requests\SectorRequest;
use Illuminate\Support\Facades\Auth;

class SectorsController extends Controller
{
    public function index()
    {
        $sectors = Sector::where('team_id', Auth::user()->current_team_id)->get();
        return view('sectors.index', compact('sectors'));
    }

    public function create()
    {
        $this->authorize('create', Sector::class);
        return view('sectors.create');
    }

    public function store(SectorRequest $request)
    {
        $this->authorize('create', Sector::class);

        try{
            Sector::create($request->all());
            $request->session()->flash('status', array('info', 'Sektor je uspešno kreiran'));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja sektora, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }
        return redirect('/sectors');
    }

    public function show($id)
    {
        $sector = Sector::findOrFail($id);
        abort(404);
    }

    public function edit($id)
    {
        $this->authorize('update', Sector::find($id));
        $sector = Sector::findOrFail($id);

        return view('sectors.edit', compact('sector'));
    }

    public function update(SectorRequest $request, $id)
    {
        $this->authorize('update', Sector::find($id));
        $sector = Sector::findOrFail($id);

        try{
            $sector->update($request->all());
            CustomLog::info('Sektor "'.$sector->name.'" izmenjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', 'Sektor je uspešno izmenjen'));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene sektora '.$sector->name.', '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }
        return redirect('/sectors');
    }

    public function destroy($id)
    {
        $sector = Sector::find($id);
        $this->authorize('delete', $sector);

        try{
            Sector::destroy($id);
            return back()->with('status', array('info', 'Sektor je uspešno uklonjen'));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja sektora "'.$sector->name.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', 'Sektor ne može biti uklonjen jer je u direktnoj vezi sa pojedinim sistemskim procesima.'));
        }
    }


}
