<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Standard;
use App\Models\Team;
use App\Facades\CustomLog;

class StandardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($teamId)
    {
        $standards =Standard::whereDoesntHave('teams', function ($team) use ($teamId){
            $team->whereId($teamId);
        })->get();
        return view('teams.add-standard-to-team', compact('standards'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $standard_id = $request->standard;
        $teamId = $request->team_id;
        
        $standard = \App\Models\Standard::find($standard_id);
        $team = Team::find($teamId);
        
        try{
            $team->standards()->attach($standard);
            CustomLog::info('Standard "'.$standard->name.'" dodat. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), $team->name);
            $request->session()->flash('status', 'Standard '.$standard->name.' uspešno dodat firmi "'.$team->name.'"');
        } catch(\Exception $e){
            CustomLog::warning('Neuspeli pokušaj dodele standarda '.$standard->name.'. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s').' Greška- '.$e->getMessage(), $team->name);
            $request->session()->flash('status', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/teams');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $team_id = $request->team_id;
        $standard = Standard::find($id);
        $team = Team::find($team_id);

        try{
            Standard::find($id)->teams()->detach($team_id);
            CustomLog::info('Standard "'.$standard->name.'" uklonjen. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), $team->name);
            return redirect('/teams')->with('status', 'Standard '.$standard->name.' uspešno uklonjen iz firme "'.$team->name.'"');
        } catch(\Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja standarda '.$standard->name.'. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s').' Greška- '.$e->getMessage(), $team->name);
            return redirect('/teams')->with('status', 'Došlo je do greške, pokušajte ponovo!');
        }
    }
}
