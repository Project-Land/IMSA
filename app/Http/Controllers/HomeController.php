<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Standard;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class HomeController extends Controller
{
    public function index() {
        $standards = Standard::all();
        return view('dashboard', compact('standards'));
    }

    public function standard($id) {
        try{
            $standard = Standard::findorFail($id);
            session(['standard' => $id]);
            return view('standard', compact('standard'));
        }
        catch(ModelNotFoundException $err){
            return redirect('/');
        }
    }
}
