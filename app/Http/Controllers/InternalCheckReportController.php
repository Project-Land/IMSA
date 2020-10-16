<?php

namespace App\Http\Controllers;

use App\Models\Inconsistency;
use Illuminate\Http\Request;
use App\Models\InternalCheck;
use App\Models\InternalCheckReport;
use App\Models\Recommendation;

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
        $validatedData = $request->validate([
            
            'internal_check_id' => 'required',
            'specification' => 'required'
            
        ]);

        $recommendationData = $request->validate([

            'newInputRecommendation1' => 'string',
            'newInputRecommendation2' => 'string',
            'newInputRecommendation3' => 'string',
            'newInputRecommendation4' => 'string'       
            
        ]);

        $InconsistencyData = $request->validate([
            'newInput1' => 'string',
            'newInput2' => 'string',
            'newInput3' => 'string',
            'newInput4' => 'string',
            'newInput4' => 'string'
            
        ]);

    

        $report=InternalCheckReport::create($validatedData);

        foreach( $InconsistencyData as $inc){
            if($inc === "")continue;
            $inconsistency=new Inconsistency();
            $inconsistency->description=$inc;
            $report->inconsistencies()->save($inconsistency);
        }
        foreach( $recommendationData as $rec){
            if($rec === "")continue;
            $recommendation=new Recommendation();
            $recommendation->description=$rec;
            $report->recommendations()->save($recommendation);
        }

        $internalCheck=InternalCheck::findOrFail($validatedData['internal_check_id']);
        $report->internalCheck()->save( $internalCheck);
        
        $request->session()->flash('status', 'Izveštaj za godišnji plan je uspešno kreiran!');
        
        return redirect('/internal-check');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $report=InternalCheckReport::findOrfail($id);
       echo $report;
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
            'specification' => 'required',
            'standard_id' => 'required',
        ]);
        
        $internal_check_report=InternalCheckReport::findOrfail($id);
        $internal_check_report->update($validatedData);
        
        $request->session()->flash('status', 'Izveštaj za godišnji plan je uspešno izmenjen!');
        
        return redirect('/internal-check');
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
