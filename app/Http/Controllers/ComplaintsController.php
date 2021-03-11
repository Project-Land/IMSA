<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Complaint;
use App\Facades\CustomLog;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ComplaintsRequest;

class ComplaintsController extends Controller
{

    public function index()
    {
        if(request()->has('standard') && request()->has('standard_name')){
            session(['standard' => request()->get('standard')]);
            session(['standard_name' => request()->get('standard_name')]);

        }
        if(session('standard') == null || session('standard_name') != "9001"){
            return redirect('/');
        }

        $complaints = Complaint::where([
                ['standard_id', session('standard')],
                ['team_id', Auth::user()->current_team_id]
            ])->get();

        return view('system_processes.complaints.index', compact('complaints'));
    }

    public function create()
    {
        if(session('standard') == null || session('standard_name') != "9001"){
            return redirect('/');
        }
        $this->authorize('create', Complaint::class);
        return view('system_processes.complaints.create');
    }

    public function store(ComplaintsRequest $request)
    {
        $this->authorize('create', Complaint::class);

        try{
            $complaint = Complaint::create($request->all());
            $notification = Notification::create([
                'message'=>__('Rok za realizaciju reklamacije ').date('d.m.Y', strtotime($complaint->deadline_date)),
                'team_id'=>Auth::user()->current_team_id,
                'checkTime' => $complaint->deadline_date
            ]);
        $complaint->notification()->save($notification);
            CustomLog::info('Reklamacija "'.$complaint->name.'" kreirana, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', __('Reklamacija je uspešno sačuvana!')));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja reklamacije, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška- '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', __('Došlo je do greške, pokušajte ponovo!')));
        }
        return redirect('/complaints');
    }

    public function show($id)
    {
        $complaint = Complaint::findOrFail($id);
        abort(404);
    }

    public function edit($id)
    {
        $this->authorize('update', Complaint::find($id));

        $complaint = Complaint::findOrFail($id);
        return view('system_processes.complaints.edit', compact('complaint'));
    }

    public function update(ComplaintsRequest $request, $id)
    {
        $this->authorize('update', Complaint::find($id));

        $complaint = Complaint::findOrFail($id);

        try{
            $complaint->update($request->all());
            $notification = $complaint->notification;
            if(!$notification){
                $notification=new Notification();
                $notification->team_id=Auth::user()->current_team_id;
            }
            $notification->message = __('Rok za realizaciju reklamacije ').date('d.m.Y', strtotime($request->deadline_date));
            $notification->checkTime = $complaint->deadline_date;
            $complaint->notification()->save($notification);
            CustomLog::info('Reklamacija "'.$complaint->name.'" izmenjena, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', __('Reklamacija je uspešno izmenjena!')));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene reklamacije "'.$complaint->name.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška- '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', __('Došlo je do greške, pokušajte ponovo!')));
        }
        return redirect('/complaints');
    }

    public function destroy($id)
    {
        $this->authorize('delete', Complaint::find($id));
        $complaint = Complaint::findOrFail($id);

        try{
            $complaint->notification()->delete();
            Complaint::destroy($id);
            CustomLog::info('Reklamacija "'.$complaint->name.'" uklonjena, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', __('Reklamacija je uspešno obrisana')));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja reklamacije "'.$complaint->name.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška- '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', __('Došlo je do greške! Pokušajte ponovo.')));
        }
    }
}
