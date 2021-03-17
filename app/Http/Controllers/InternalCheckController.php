<?php

namespace App\Http\Controllers;


use Exception;
use App\Models\Team;
use App\Models\PlanIp;
use App\Facades\CustomLog;
use Illuminate\Support\Str;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\InternalCheck;
use Illuminate\Support\Facades\DB;
use App\Models\InternalCheckReport;

use App\Exports\InternalCheckExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\StoreInternalCheckRequest;
use App\Http\Requests\UpdateInternalCheckRequest;


class InternalCheckController extends Controller
{

    public function index()
    {
        if(request()->has('standard') && request()->has('standard_name')){
            session(['standard' => request()->get('standard')]);
            session(['standard_name' => request()->get('standard_name')]);
        }

        if(empty(session('standard'))){
            return redirect('/')->with('status', array('secondary', 'Izaberite standard!'));
        }

        $internal_checks = InternalCheck::where([
                ['standard_id', session('standard')],
                ['team_id', Auth::user()->current_team_id]
            ])->whereYear('date', '=', date('Y'))->with(['sector','standard','planIp','user'])->orderBy('date', 'desc')->get();

       

        return view('system_processes.internal_check.index', ['internal_checks' => $internal_checks]);
    }

    public function getData(Request $request, $year)
    {
        $internal_checks = InternalCheck::where([
            ['standard_id', session('standard')],
            ['team_id', Auth::user()->current_team_id],
        ])->whereYear('date', '=', $year)->with(['sector','standard','planIp'])->orderBy('date', 'desc')->get();

        $request->session()->flash('year', $year);
        return view('system_processes.internal_check.index', compact('internal_checks'));
    }

    public function create()
    {
        if(empty(session('standard'))){
            return redirect('/');
        }
        $this->authorize('create', InternalCheck::class);

        $team = Team::findOrFail(Auth::user()->current_team_id);
        $sectors = $team->sectors;
        $teamLeaders = $team->users;

        $leaders = $teamLeaders->filter(function ($value) {
            return ($value->allTeams()->first()->membership->role === 'editor'|| $value->allTeams()->first()->membership->role === 'admin') && $value->certificates->pluck('name')->contains('editor');
        });

        return view('system_processes.internal_check.create',
            [
                'sectors' => $sectors,
                'teamLeaders' => $leaders
            ]
        );
    }

    public function store(StoreInternalCheckRequest $request)
    {

        $this->authorize('create',InternalCheck::class);

        $validatedData = $request->validated();
        $validatedLeaders = $request->validate([ 'leaders' => 'required']);
        $leaders = implode(",", $validatedLeaders['leaders']);

        //Calculate planIp name
        $c = DB::table('plan_ips')
        ->join('internal_checks', 'plan_ips.id', '=', 'internal_checks.plan_ip_id')
        ->where('internal_checks.team_id','<>', Auth::user()->current_team_id)->get()->count();

        $ctest = DB::table('plan_ips')
        ->join('internal_checks', 'plan_ips.id', '=', 'internal_checks.plan_ip_id')
        ->where('internal_checks.team_id', Auth::user()->current_team_id)
        ->where('internal_checks.standard_id', session('standard'))
        ->whereYear('internal_checks.date', date('Y', strtotime($request->date)))
        ->get()->count();

        $lastid = PlanIp::latest()->first();
        if(!$lastid){
            $planId = 1;
        } else{
            if($lastid->id == 1){
                $planId = 2;
            } else{
                $planId = $lastid->id - $c + 1;
            }
        }

        $validatedData['leaders'] = $leaders;
        $validatedData['team_id'] = Auth::user()->current_team_id;
        $validatedData['user_id'] = Auth::user()->id;
        $validatedData['date'] = date('Y-m-d', strtotime($request->date));

        try{
            DB::transaction(function () use ($request, $validatedData, $planId){
                $internalCheck=InternalCheck::create($validatedData);
                $notification=Notification::create([
                'message'=>__('Interna provera za ').date('d.m.Y', strtotime($internalCheck->date)),
                'team_id'=>Auth::user()->current_team_id,
                'checkTime' => $internalCheck->date
                ]);
                $internalCheck->notification()->save($notification);

                $planIp = new PlanIp();
                $planIp->standard_id = $request->standard_id;
                $planIp->save();
                $planIp->name = $planId.'/'.date('Y');
                $planIp->save();

                $planIp->internalCheck()->save($internalCheck);
                CustomLog::info('Godišnji plan interne provere id: "'.$internalCheck->id.'" je kreiran, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
                $request->session()->flash('status', array('info', 'Godišnji plan interne provere je uspešno kreiran!'));
            });
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja godišnjeg plana interne provere, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }

        return redirect('/internal-check');
    }

    public function show($id)
    {
        abort(404);
    }

    public function edit($id)
    {
        $internal_check = InternalCheck::with(['sector','standard','planIp'])->findOrfail($id);
        $this->authorize('update', $internal_check);

        $team = Team::findOrFail(Auth::user()->current_team_id);
        $sectors = $team->sectors;
        $teamLeaders = $team->users;

        $leaders = $teamLeaders->filter(function ($value) {
            return ($value->allTeams()->first()->membership->role === 'editor' || $value->allTeams()->first()->membership->role === 'admin') && $value->certificates->pluck('name')->contains('editor');
        });
        $leaders_names=explode(',',$internal_check->leaders);
        return view('system_processes.internal_check.edit',
            [
                'internalCheck' => $internal_check,
                'sectors'=> $sectors,
                'teamLeaders' => $leaders,
                'leaders_names' => $leaders_names            ]
        );
    }

    public function update(UpdateInternalCheckRequest $request, $id)
    {
        $internal_check = InternalCheck::findOrfail($id);

        $this->authorize('update', $internal_check);
        $validatedLeaders = $request->validate([ 'leaders' => 'required']);
        $leaders = implode(",", $validatedLeaders['leaders']);
        $validatedData =  $request->validated();
        $validatedData['date'] = date('Y-m-d', strtotime($request->date));
        $validatedData['leaders'] = $leaders;

        try{
            $internal_check->update($validatedData);
            $pip=$internal_check->planIp;
            $pip->team_for_internal_check= $validatedData['leaders'];
            $pip->save();
            $notification = $internal_check->notification;
            if(!$notification){
                $notification=new Notification();
                $notification->team_id=Auth::user()->current_team_id;
            }
            $notification->message = __('Interna provera za ').date('d.m.Y', strtotime($request->date));
            $notification->checkTime = $internal_check->date;
            $internal_check->notification()->save($notification);

            CustomLog::info('Godišnji plan interne provere id: "'.$internal_check->id.'" je izmenjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', 'Godišnji plan interne provere je uspešno izmenjen!'));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene godišnjeg plana interne provere id: "'.$internal_check->id.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }
        return redirect('/internal-check/get-data/'.date('Y',strtotime($request->date)));
    }

    public function destroy($id)
    {
        $internal_check = InternalCheck::findOrfail($id);
        $this->authorize('delete', $internal_check);

        try{
            $internal_check->notification()->delete();
            InternalCheck::destroy($id);
           if($internal_check->plan_ip_id){
                PlanIp::destroy($internal_check->plan_ip_id);
           }
            $report=InternalCheckReport::find($internal_check->internal_check_report_id);
            if($report){
                $report->correctiveMeasures()->delete();
                InternalCheckReport::destroy($internal_check->internal_check_report_id);
            }
           

            CustomLog::info('Godišnji plan interne provere id: "'.$internal_check->id.'" je obrisan, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', 'Godišnji plan interne provere je uspešno uklonjen'));

        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja godišnjeg plana interne provere id: "'.$internal_check->id.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }
    }

    public function export(Request $request)
    {
        if(empty(session('standard'))){
            return redirect('/');
        }
        if($request->year == null){
            $request->year=date('Y');
        }

        return Excel::download(new InternalCheckExport($request->year), Str::snake(__('Interne provere')).'_'.session('standard_name').'.xlsx');
    }
}
