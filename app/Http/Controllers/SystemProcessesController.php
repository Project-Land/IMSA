<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemProcess;
use App\Models\Standard;
use Illuminate\Database\Eloquent\Builder;
use Exception;

class SystemProcessesController extends Controller
{

    public function index()
    {
        abort(404);
    }

    public function getByStandard(Request $request)
    {
        $standard_id = $request->data['standard'];
        $standard = Standard::findOrFail($standard_id);

        $sp = SystemProcess::whereDoesntHave('standards', function (Builder $query) use ($standard_id){
            $query->where('standard_id', $standard_id);
        })->get();

        return response()->json($sp);
    }

    public function create()
    {
        abort(404);
    }

    public function addToStandard()
    {
        $standards = Standard::all();

        return view('sp_management.add_to_standard', compact('standards'));
    }

    public function storeToStandard(Request $request)
    {
        $system_process_id = $request->system_process;
        $standard = Standard::findOrFail($request->standard);

        try{
            $standard->systemProcess()->attach($system_process_id);
            return back()->with('status', 'Sistemski proces uspešno dodat standardu '. $standard->name);
        }
        catch (Exception $e){
            return back()->with('status', 'Došlo je do greške, pokušajte ponovo.');
        }
        
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //abort(404);
    }

    public function edit($id)
    {
        abort(404);
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        abort(404);
    }
}
