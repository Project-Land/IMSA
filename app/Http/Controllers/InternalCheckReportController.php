<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InternalCheck;
use App\Models\InternalCheckReport;

class InternalCheckReportController extends Controller
{
    public function index()
    {   
        $internal_checks=InternalCheckReport::all();
        return view('system_processes.internal_check.index',['internal_checks'=>$internal_checks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createReport($id)
    {
        $internalCheck=InternalCheck::findOrFail($id);
        return view('system_processes.internal_check_report.create',['internalCheck'=>$internalCheck]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       /* $validatedData = $request->validate([
            'date' => 'required',
            'sector' => 'required',
            'leaders' => 'required',
            'standard_id' => 'required',
        ]);*/
        
        $request->session()->flash('status', 'Izveštaj za godišnji plan je uspešno kreiran!');
        
        return redirect('/internal-check-report');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $internal_check_report=InternalCheckReport::findOrFail($id);
        return view('system_processes.internal_check_report.create',['internalCheckReport'=>$internal_check_report]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'sector' => 'required',
            'leaders' => 'required',
            'standard_id' => 'required',
        ]);
        
        $internal_check_report=InternalCheckReport::findOrfail($id);
        $internal_check_report->update($validatedData);
        
        $request->session()->flash('status', 'Izveštaj za godišnji plan je uspešno izmenjen!');
        
        return redirect('/internal-check-report');
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        InternalCheckReport::destroy($id);
        return back()->with('status', 'Izveštaj za godišnji plan je uspešno uklonjen');
    }
}
