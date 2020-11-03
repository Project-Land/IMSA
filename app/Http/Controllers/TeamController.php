<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;

class TeamController extends Controller
{
    public function index()
    {
        $this->authorize('viewAllTeams', Team::class);
        $teams = Team::with('standards')->get();
        
        return view('teams.index', compact('teams'));
    }
    
}
