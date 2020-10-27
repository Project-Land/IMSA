<?php

namespace App\Http\Controllers;

use App\Models\PlanIp;
use Illuminate\Http\Request;

class PlanIpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $plan_ip=PlanIp::findOrFail($id);
        echo $plan_ip;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $plan_ip=PlanIp::findOrFail($id);
        $this->authorize('update',$plan_ip);
        return view('system_processes.plan_ip.edit',['planIp'=> $plan_ip]);
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
        $planIp=PlanIp::findOrFail($id);
        $this->authorize('update',$planIp);
        $validated=$request->validate([
            'checked_date'=>'required|date',
            'checked_sector'=>'required',
            'team_for_internal_check'=>'required',
            'check_start'=>'required|date',
            'check_end'=>'required|date',
            'report_deadline'=>'required|date'
        ]);
        $planIp->report_deadline = $request->report_deadline;
        $planIp->check_start = date('Y-m-d H:i', strtotime($request->check_start));
        $planIp->check_end = date('Y-m-d H:i', strtotime($request->check_end));
        $planIp->checked_date = $request->checked_date;
        $planIp->checked_sector = $request->checked_sector;
        $planIp->team_for_internal_check = $request->team_for_internal_check;
        $planIp->save();
        
        //$planIp->update($validated);

        return redirect('/internal-check')->with('status', 'Plan IP je izmenjen!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   $plan_ip=PlanIp::findOrFail($id);
        $this->authorize('update',$plan_ip);
        PlanIp::destroy($id);
        return back()->with('status', 'Plan IP je uspešno uklonjen');
    }
}
