<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Standard;
use App\Models\Team;
use App\Facades\CustomLog;

class StandardsController extends Controller
{

    public function index()
    {
        abort(404);
    }

    public function create($teamId)
    {
        $this->authorize('create', Standard::class);
        $standards =Standard::whereDoesntHave('teams', function ($team) use ($teamId){
            $team->whereId($teamId);
        })->get();
        return view('teams.add-standard-to-team', compact('standards'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Standard::class);

        $standard_id = $request->standard;
        $teamId = $request->team_id;
        
        $standard = \App\Models\Standard::find($standard_id);
        $team = Team::find($teamId);
        
        try{
            $team->standards()->attach($standard);
            CustomLog::info('Standard "'.$standard->name.'" dodat, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s'), $team->name);
            $request->session()->flash('status', 'Standard '.$standard->name.' uspešno dodat firmi "'.$team->name.'"');
        } catch(\Exception $e){
            CustomLog::warning('Neuspeli pokušaj dodele standarda '.$standard->name.', '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s').' Greška: '.$e->getMessage(), $team->name);
            $request->session()->flash('status', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/teams');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy(Request $request, $id)
    {
        $this->authorize('delete', Standard::find($id));

        $team_id = $request->team_id;
        $standard = Standard::find($id);
        $team = Team::find($team_id);

        try{
            Standard::find($id)->teams()->detach($team_id);
            CustomLog::info('Standard "'.$standard->name.'" uklonjen, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s'), $team->name);
            return redirect('/teams')->with('status', 'Standard '.$standard->name.' uspešno uklonjen iz firme "'.$team->name.'"');
        } catch(\Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja standarda '.$standard->name.', '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), $team->name);
            return redirect('/teams')->with('status', 'Došlo je do greške, pokušajte ponovo!');
        }
    }
}
