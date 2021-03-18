<?php

namespace App\Http\Controllers;


use App\Models\Standard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class HomeController extends Controller
{

    public function __construct()
    {

    }
    public function index()
    {
        if(!session()->has('locale')){
            session(['locale' => 'sr']);
        }
        session()->forget('standard');
        session()->forget('standard_name');
        $teamId = Auth::user()->current_team_id;
        $standards = Standard::whereHas('teams', function($q) use ($teamId) {
            $q->where('team_id', $teamId);
         })->orderByRaw('LENGTH(name)', 'ASC')
         ->orderBy('name', 'ASC')->get();
        return view('dashboard', compact('standards'));
    }

    public function standard($id)
    {
        try{
            $teamId = Auth::user()->current_team_id;
            $standard = Standard::whereHas('teams', function($q) use ($teamId) {
                $q->where('team_id', $teamId);
            })->findOrFail($id);

            session(['standard' => $id]);
            session(['standard_name' => $standard->name]);
            return view('standard', compact('standard'));
        }
        catch (ModelNotFoundException $e){
            return redirect('/')->with('status', array('secondary', __('Izaberite standard!')));
        }
    }

    public function analytics()
    {
        $role = Auth::user()->allTeams()->first()->membership->role;
        if($role == "super-admin") {
            return file_get_contents(storage_path('log.html'));
        } else abort(404);
    }

    public function document_download(Request $request)
    {
        $folder = $request->folder;
        $file_name = $request->file_name;

        $path = storage_path().'/'.'app'.'/'.$folder.'/'.$file_name;

        if (file_exists($path)) {
            return Response::download($path);
        }
        else {
            return back()->with('status', array('danger', __('Fajl nije pronađen')));
        }
    }

    public function document_preview(Request $request)
    {
        $folder = $request->folder;
        $file_name = $request->file_name;

        $path = storage_path().'/'.'app'.'/'.$folder.'/'.$file_name;

        if (file_exists($path)) {
            return Response::file($path);
        }
        else {
            return back()->with('status', array('danger', __('Fajl nije pronađen')));
        }
    }

    public function about(){
        return redirect('/images/imsa.pdf');
    }

    public function contact(){

        return view('guest.contact');
    }

    public function manual()
    {
        return view('manual');
    }

    public function lang($lang)
    {
        session(['locale' => $lang]);
        return back();
    }

}
