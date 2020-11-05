<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\TeamStats;

class TeamController extends Controller
{
    public function index()
    {
        $this->authorize('viewAllTeams', Team::class);
        $teams = Team::with('standards')->get();
        
        return view('teams.index', compact('teams'));
    }

    public function showTeamUserStats($id)
    {
        $stats = TeamStats::where('team_id', $id)->with('team')->orderBy('check_date', 'desc')->get();
        return response()->json($stats);
    }
    
}
