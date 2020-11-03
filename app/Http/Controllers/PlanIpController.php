<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\PlanIp;
use App\Facades\CustomLog;
use App\Http\Requests\UpdatePlanIpRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanIpController extends Controller
{

    public function index()
    {
        //
    }

    public function create()
    {
       
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $plan_ip = PlanIp::findOrFail($id);
        echo $plan_ip;
    }

    public function edit($id)
    {
        $plan_ip = PlanIp::findOrFail($id);
        $this->authorize('update', $plan_ip);
        return view('system_processes.plan_ip.edit', ['planIp' => $plan_ip]);
    }

    public function update(UpdatePlanIpRequest $request, $id)
    {
        $planIp = PlanIp::findOrFail($id);
        $this->authorize('update', $planIp);

        $planIp->report_deadline = $request->report_deadline;
        $planIp->check_start = date('Y-m-d H:i', strtotime($request->check_start));
        $planIp->check_end = date('Y-m-d H:i', strtotime($request->check_end));
        $planIp->checked_date = $request->checked_date;
        $planIp->checked_sector = $request->checked_sector;
        $planIp->team_for_internal_check = $request->team_for_internal_check;

        try{
            $planIp->save();
            CustomLog::info('Plan IP id-"'.$planIp->id.'" je sačuvan, '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Dokument je uspešno uklonjen');
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene plana IP id-'.$planIp->id.', '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Došlo je do greške, pokušajte ponovo!');
        }
   
        return redirect('/internal-check')->with('status', 'Plan IP je izmenjen!');
    }

    public function destroy($id)
    {
        $plan_ip = PlanIp::findOrFail($id);
        $this->authorize('delete', $plan_ip);

        try{
            PlanIp::destroy($id);
            CustomLog::info('Plan IP id-'.$plan_ip->id.' je uklonjen, '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
            return back()->with('status', 'Plan IP je uspešno uklonjen');
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja plana IP id-'.$plan_ip->id.', '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
            return back()->with('status', 'Došlo je do greške, pokušajte ponovo');
        }
    }
}
