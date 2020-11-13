<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Standard;
use App\Models\SystemProcess;
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

            $system_processes = SystemProcess::whereHas('standards', function($q) use($id) {
                $q->where('standard_id', $id);
            })->orderBy('display_order')->get();
            
            session(['standard' => $id]);
            session(['standard_name' => $standard->name]);
            return view('standard', compact('standard', 'system_processes'));
        }
        catch (ModelNotFoundException $e){
            return redirect('/');
        }
    }

    public function analytics()
    {
        $role = \Auth::user()->allTeams()->first()->membership->role;
        if($role == "super-admin") {
            return file_get_contents(storage_path('log.html'));
        }else abort(404);
    }

    public function document_download(Request $request)
    {
        $folder = $request->folder;
        $file_name = $request->file_name;

        $path = storage_path().'/'.'app'.'/'.$folder.'/'.$file_name;
        
        if (file_exists($path)) {
            return \Response::download($path);
           
        }
        else {
            return back()->with('warning', 'Fajl nije pronađen');
        }
    }

}
