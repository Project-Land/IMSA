<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::with('standards')->get();
        return view('teams.index', compact('teams'));
    }
    
    public function addNewStandard($teamId)
    {
        $standards = \App\Models\Standard::whereDoesntHave('teams', function ($team) use ($teamId){
            $team->whereId($teamId);
        })->get();
        return view('teams.add-standard-to-team', compact('standards'));
    }

    public function storeNewStandard(Request $request)
    {
        $standard_id = $request->standard;
        $teamId = $request->team_id;
        
        $standard = \App\Models\Standard::find($standard_id);
        $team = Team::find($teamId);
        
        $team->standards()->attach($standard);
        return redirect('/teams');
    }
}
