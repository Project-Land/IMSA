<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\TeamStats;
use App\Models\Certificate;

class TeamController extends Controller
{
    public function index()
    {
        $this->authorize('viewAllTeams', Team::class);
        $teams = Team::with('standards')->orderByRaw('LENGTH(name)', 'ASC')->orderBy('name', 'ASC')->get();

        return view('teams.index', compact('teams'));
    }

    public function showTeamUserStats($id)
    {
        $stats = TeamStats::where('team_id', $id)->with('team')->orderBy('check_date', 'desc')->get();
        return response()->json($stats);
    }

    public function getAllCertificates()
    {
        $certificates = Certificate::all();

        $translatedCertificates = $certificates->map(function($cert, $key) {
            $cert->name = __($cert->name);

            return $cert;
        });
        return response()->json($translatedCertificates);
    }

}
