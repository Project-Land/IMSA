<?php

namespace App\Http\Controllers;

use App\Models\Recommendation;
use Illuminate\Http\Request;

class RecommendationsController extends Controller
{

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
    /*  $rec=Recommendation::findOrFail($id);
        $this->authorize('delete',$rec);
        $report=$rec->report->id;
        $rec->delete();
        return view('system_processes.internal_check_report.edit',['internalCheckReport'=>$report]);
    */
    }
}
