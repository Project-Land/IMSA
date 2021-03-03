<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Soa;

class SoaController extends Controller
{
    public function index()
    {
        return View('system_processes.statement_of_applicability.index');
    }

    public function edit($teamId){
        $fields = Soa::where('team_id', $teamId)->get();
        return View('system_processes.statement_of_applicability.edit', compact('fields'));
    }
}
