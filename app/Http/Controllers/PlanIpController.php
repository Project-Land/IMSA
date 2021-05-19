<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Team;
use App\Models\User;
use App\Models\PlanIp;
use App\Facades\CustomLog;
use App\Mail\PlanIpCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\UpdatePlanIpRequest;
use App\Notifications\PlanIpInstantNotification;

class PlanIpController extends Controller
{

    public function index()
    {
        abort(404);
    }

    public function create()
    {
        abort(404);
    }

    public function store(Request $request)
    {
        abort(404);
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
        $team = Team::findOrFail(Auth::user()->current_team_id);
        $users = $team->users->filter(function ($value) {
            return ($value->allTeams()->first()->membership->role != 'super-admin');
        });
        return view(
            'system_processes.plan_ip.edit',
            [
                'planIp' => $plan_ip,
                'users' => $users
            ]
        );
    }

    public function update(UpdatePlanIpRequest $request, $id)
    {
        $planIp = PlanIp::findOrFail($id);
        $this->authorize('update', $planIp);

        $planIp->report_deadline = date('Y-m-d', strtotime($request->report_deadline));
        $planIp->check_start = date('Y-m-d H:i', strtotime($request->check_start));
        $planIp->check_end = date('Y-m-d H:i', strtotime($request->check_end));
        $planIp->checked_date = date('Y-m-d', strtotime($request->checked_date));
        $planIp->checked_sector = $request->checked_sector;
        $planIp->team_for_internal_check = $request->team_for_internal_check;
        $planIp->check_users = $request->check_users;

        $not = new PlanIpInstantNotification($planIp);
        $not->save();
        $not->user()->sync($request->check_users);

        foreach ($request->check_users as $u) {
            $user = User::findOrFail($u);
            if ($user->email != null) {
                $standard_name = session('standard_name');
                $url = 'https://quality4.me/internal-check?standard=' . $planIp->standard_id . '?internal-check#internalcheck"' . $planIp->internalCheck->id;
                Mail::to($user->email)->send(new PlanIpCreated($planIp, $url, $standard_name));
            }
        }

        try {
            $planIp->save();
            CustomLog::info('Plan IP id: "' . $planIp->id . '" je sačuvan, ' . Auth::user()->name . ', ' . Auth::user()->username . ', ' . date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', 'Plan IP je uspešno izmenjen'));
        } catch (Exception $e) {
            CustomLog::warning('Neuspeli pokušaj izmene plana IP id: ' . $planIp->id . ', ' . Auth::user()->name . ', ' . Auth::user()->username . ', ' . date('d.m.Y H:i:s') . ', Greška: ' . $e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }

        return redirect('/internal-check');
    }

    public function destroy($id)
    {
        $plan_ip = PlanIp::findOrFail($id);
        $this->authorize('delete', $plan_ip);

        try {
            PlanIp::destroy($id);
            CustomLog::info('Plan IP id: ' . $plan_ip->id . ' je uklonjen, ' . Auth::user()->name . ', ' . Auth::user()->username . ', ' . date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', 'Plan IP je uspešno uklonjen'));
        } catch (Exception $e) {
            CustomLog::warning('Neuspeli pokušaj brisanja plana IP id: ' . $plan_ip->id . ', ' . Auth::user()->name . ', ' . Auth::user()->username . ', ' . date('d.m.Y H:i:s') . ', Greška: ' . $e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }
    }
}
