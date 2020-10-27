<?php

namespace App\Http\Controllers;


use Exception;
use Carbon\Carbon;
use App\Models\Team;
use App\Models\User;
use App\Models\PlanIp;
use App\Models\Standard;
use App\Models\Supplier;
use App\Facades\CustomLog;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\InternalCheck;
use Faker\Provider\ar_JO\Internet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Throwable;

class InternalCheckController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    
    public function index()
    {   
        $user=Auth::user();
        $internal_checks=InternalCheck::where('team_id',$user->current_team_id)->get();
        return view('system_processes.internal_check.index',['internal_checks'=>$internal_checks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { 
        $this->authorize('create',InternalCheck::class);
        $team=Team::findOrFail(Auth::user()->current_team_id);
        $sectors=$team->sectors;
        $teamLeaders=$team->users;
        $leaders = $teamLeaders->filter(function ($value) {  
            return $value->allTeams()->first()->membership->role==='editor';
        });
        
        return view('system_processes.internal_check.create',['sectors'=>$sectors,'teamLeaders'=>$leaders]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create',InternalCheck::class);
        $validatedData = $request->validate([
            'date' => 'required',
            'sector_id' => 'required',
            'standard_id' => 'required',
        ]);

        $validatedLeaders=$request->validate([ 'leaders' => 'required']);
        $leaders=implode(",",$validatedLeaders['leaders']);
        $validatedData['leaders']=$leaders;
        $validatedData['team_id']=Auth::user()->current_team_id;
        try{
            DB::transaction(function () use ($request,$validatedData){
                $internalCheck=InternalCheck::create($validatedData);
                $notification=Notification::create([
                'message'=>'Interna provera za '.$internalCheck->date,
                'team_id'=>Auth::user()->current_team_id,
                'checkTime' => $internalCheck->date
                ]);
                $internalCheck->notification()->save($notification);
                $planIp=new PlanIp();
                $planIp->save();
                $planIp->name=$planIp->id.'/'.date('Y');
                $planIp->save();
                $planIp->internalCheck()->save($internalCheck);
                CustomLog::info('Interna provera id-"'.$internalCheck->id.'" je kreirana. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
                $request->session()->flash('status', 'Godišnji plan je uspešno kreiran!');
            });
            }catch(Exception $e){
                CustomLog::warning('Neuspeli pokušaj kreiranja interne provere. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s').' Greška- '.$e->getMessage(), 'Firma-'.\Auth::user()->current_team_id);
                $request->session()->flash('status', 'Došlo je do greške, pokušajte ponovo!');
            }
        
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
        $internal_check=InternalCheck::findOrFail($id);
        $this->authorize('update',$internal_check);
        return view('system_processes.internal_check.edit',['internalCheck'=>$internal_check]);
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

        $internal_check=InternalCheck::findOrfail($id);
        $this->authorize('update',$internal_check);
        $validatedData = $request->validate([
            'sector_id' => 'required',
            'leaders' => 'required',
            'standard_id' => 'required',
            'date'=> 'required|date'
        ]);
        try{
            $internal_check->update($validatedData); 
            $notification=$internal_check->notification;
            $notification->message='Interna provera za '.$internal_check->date;
            $notification->checkTime = $internal_check->date;
            $internal_check->notification()->save($notification);
            $request->session()->flash('status', 'Godišnji plan je uspešno izmenjen!'); 
            CustomLog::info('Interna provera id-"'.$internal_check->id.'" je izmenjena. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
                $request->session()->flash('status', 'Godišnji plan je uspešno kreiran!');
        }catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene interne provere. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s').' Greška- '.$e->getMessage(), 'Firma-'.\Auth::user()->current_team_id);
            $request->session()->flash('status', 'Došlo je do greške, pokušajte ponovo!');
        }
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
        $internal_check=InternalCheck::findOrfail($id);
        $this->authorize('delete',$internal_check);
        try{
            InternalCheck::destroy($id);
            CustomLog::info('Godišnji plan interne provere id-"'.$internal_check_report->id.'" je obrisan. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
            return back()->with('status', 'Godišnji plan je uspešno uklonjen');
        }catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja godišnjeg plana interne provere. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s').' Greška- '.$e->getMessage(), 'Firma-'.\Auth::user()->current_team_id);
            return back()->with('status', 'Došlo je do greške, pokušajte ponovo');
        }
    }
}
