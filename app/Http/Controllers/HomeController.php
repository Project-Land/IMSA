<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Standard;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class HomeController extends Controller
{
    public function index()
    {
        session()->forget('standard');
        session()->forget('standard_name');
        $teamId = \Auth::user()->current_team_id;
        $standards = Standard::whereHas('teams', function($q) use ($teamId) {
            $q->where('team_id', $teamId);
         })->get();
        return view('dashboard', compact('standards'));
    }

    public function standard($id)
    {
        try{
            $teamId = \Auth::user()->current_team_id;
            $standard = Standard::whereHas('teams', function($q) use ($teamId) {
                $q->where('team_id', $teamId);
            })->findOrFail($id);
            
            session(['standard' => $id]);
            session(['standard_name' => $standard->name]);
            return view('standard', compact('standard'));
        }
        catch(ModelNotFoundException $e){
            return redirect('/');
        }
    }

    public function analytics(){
        
        $role = \Auth::user()->allTeams()->first()->membership->role;
        if($role == "super-admin") {
            return file_get_contents(storage_path('log.html'));
        }else exit();
    }
}
