<?php

namespace App\Http\Controllers;


use Exception;
use Throwable;
use Carbon\Carbon;
use App\Models\Team;
use App\Models\User;
use App\Models\PlanIp;
use App\Models\InternalCheckReport;

use App\Models\Standard;
use App\Models\Supplier;
use App\Facades\CustomLog;
use App\Http\Requests\StoreInternalCheckRequest;
use App\Http\Requests\UpdateInternalCheckRequest;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\InternalCheck;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class InternalCheckController extends Controller
{

    public function index()
    {   
        $standardId = $this::getStandard();
        if($standardId == null){
            return redirect('/')->with('status', 'Izaberite standard!');
        }

        $internal_checks = InternalCheck::where([
                ['standard_id', $standardId],
                ['team_id', Auth::user()->current_team_id]
            ])->whereYear('date', '=', date('Y'))->get();

        return view('system_processes.internal_check.index', ['internal_checks' => $internal_checks]);
    }

    public function getData(Request $request,$year) {
        $standardId = $this::getStandard();
        
        $internal_checks = InternalCheck::where([
            ['standard_id', $standardId],
            ['team_id', Auth::user()->current_team_id],
        ])->whereYear('date', '=', $year)->get();
        $request->session()->flash('year', $year);

        return view('system_processes.internal_check.index', compact('internal_checks'));
    }

    public function create()
    { 
        $this->authorize('create', InternalCheck::class);

        $team = Team::findOrFail(Auth::user()->current_team_id);
        $sectors = $team->sectors;
        $teamLeaders = $team->users;

        $leaders = $teamLeaders->filter(function ($value) {  
            return $value->allTeams()->first()->membership->role === 'editor';
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
        //Calculate planIp name
         $c=DB::table('plan_ips')
        ->join('internal_checks', 'plan_ips.id', '=', 'internal_checks.plan_ip_id')
        ->where('internal_checks.team_id','<>',Auth::user()->current_team_id)->get()->count();
        $lastid = PlanIp::latest()->first();
        if(!$lastid){
            $planId=1;
        }else{
            if($lastid->id==1){
                $planId=2;
            }else{
                $planId=$lastid->id-$c+1;
            }
        }

        $this->authorize('create',InternalCheck::class);
        $validatedData = $request->validated();
        $validatedLeaders = $request->validate([ 'leaders' => 'required']);
        $leaders = implode(",", $validatedLeaders['leaders']);

        $validatedData['leaders'] = $leaders;
        $validatedData['team_id'] = Auth::user()->current_team_id;
        $validatedData['user_id'] = Auth::user()->id;
        $validatedData['date'] = date('Y-m-d', strtotime($request->date));

        try{
            DB::transaction(function () use ($request,$validatedData,$planId){
                $internalCheck=InternalCheck::create($validatedData);
                $notification=Notification::create([
                'message'=>'Interna provera za '.date('d.m.Y', strtotime($internalCheck->date)),
                'team_id'=>Auth::user()->current_team_id,
                'checkTime' => $internalCheck->date
                ]);
                $internalCheck->notification()->save($notification);

                $planIp = new PlanIp();
                $planIp->standard_id = $request->standard_id;
                $planIp->save();
                $planIp->name=$planId.'/'.date('Y');
                $planIp->save();
                
                $planIp->internalCheck()->save($internalCheck);
                CustomLog::info('Interna provera id-"'.$internalCheck->id.'" je kreirana, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
                $request->session()->flash('status', 'Godišnji plan je uspešno kreiran!');
            });
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja interne provere, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
            $request->session()->flash('warning', 'Došlo je do greške, pokušajte ponovo!');
        }
        
        return redirect('/internal-check');
    }

    public function show($id)
    {
        $internal_check = InternalCheck::findOrFail($id);
        abort(404);
    }

    public function edit($id)
    {
        $internal_check = InternalCheck::findOrFail($id);
        $this->authorize('update', $internal_check);

        $team = Team::findOrFail(Auth::user()->current_team_id);
        $sectors = $team->sectors;
        $teamLeaders = $team->users;

        $leaders = $teamLeaders->filter(function ($value) {  
            return $value->allTeams()->first()->membership->role === 'editor';
        });

        return view('system_processes.internal_check.edit',
            [
                'internalCheck' => $internal_check,
                'sectors'=> $sectors,
                'teamLeaders' => $leaders
            ]
        );
    }

    public function update(UpdateInternalCheckRequest $request, $id)
    {
        $internal_check = InternalCheck::findOrfail($id);
        $this->authorize('update', $internal_check);

        $validatedData =  $request->validated();
        $validatedData['date'] = date('Y-m-d', strtotime($request->date));

        try{
            $internal_check->update($validatedData); 
            $notification=$internal_check->notification;
            $notification->message='Interna provera za '.date('d.m.Y', strtotime($request->date));
            $notification->checkTime = $internal_check->date;
            $internal_check->notification()->save($notification);

            $request->session()->flash('status', 'Godišnji plan je uspešno izmenjen!'); 
            CustomLog::info('Interna provera id-"'.$internal_check->id.'" je izmenjena, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Godišnji plan je uspešno izmenjen!');
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene interne provere id-"'.$internal_check->id.'", '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
            $request->session()->flash('warning', 'Došlo je do greške, pokušajte ponovo!');
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
            PlanIp::destroy($internal_check->plan_ip_id);
            InternalCheckReport::destroy($internal_check->internal_check_report_id);
           
            CustomLog::info('Godišnji plan interne provere id-"'.$internal_check->id.'" je obrisan, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
            return back()->with('status', 'Godišnji plan je uspešno uklonjen');
          
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja godišnjeg plana interne provere id-"'.$internal_check->id.'", '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
            return back()->with('warning', 'Došlo je do greške, pokušajte ponovo');
        }
    }
}
