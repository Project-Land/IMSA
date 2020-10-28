<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\PlanIp;
use App\Facades\CustomLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        try{
        $planIp->save();
        CustomLog::info('Plan IP id-"'.$planIp->id.'" je kreiran. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
        $request->session()->flash('status', 'Dokument je uspešno uklonjen');
        }catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene plana IP id-'.$planIp->id.'. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s').' Greška- '.$e->getMessage(), 'Firma-'.\Auth::user()->current_team_id);
            $request->session()->flash('status', 'Došlo je do greške, pokušajte ponovo!');
        }
        
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
        if(PlanIp::destroy($id)){
            CustomLog::info('Plan IP id-'.$plan_ip->id.' je uklonjen. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
            return back()->with('status', 'Plan IP je uspešno uklonjen');
        };
        return back()->with('status', 'Došlo je do greške, pokušajte ponovo');
       
    }
}
