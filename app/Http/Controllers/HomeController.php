<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Standard;

class HomeController extends Controller
{
    public function index() {
        $standards = Standard::all();
        return view('dashboard', compact('standards'));
    }
}
