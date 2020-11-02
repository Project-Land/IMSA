<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Complaint;
use App\Facades\CustomLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ComplaintsRequest;
use App\Http\Requests\UpdateComplaintRequest;

class ComplaintsController extends Controller
{

    public function index()
    {
        $standardId = $this::getStandard();
        if($standardId == null){
            return redirect('/');
        }

        $complaints = Complaint::where([ ['standard_id', $standardId], ['team_id',Auth::user()->current_team_id] ])->get();
        return view('system_processes.complaints.index', compact('complaints'));
    }

    public function create()
    {
        $this->authorize('create', Complaint::class);
        return view('system_processes.complaints.create');
    }

    public function store(ComplaintsRequest $request)
    {
        $this->authorize('create', Complaint::class);

        try{
            $complaint = Complaint::create($request->all());
            CustomLog::info('Reklamacija "'.$complaint->name.'" kreirana. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Reklamacija je uspešno sačuvana!');
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja reklamacije. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s').' Greška- '.$e->getMessage(), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Došlo je do greške, pokušajte ponovo!');
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
            CustomLog::info('Reklamacija "'.$complaint->name.'" izmenjena. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Reklamacija je uspešno izmenjena!');
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene reklamacije "'.$complaint->name.'". Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s').' Greška- '.$e->getMessage(), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/complaints');
    }

    public function destroy($id)
    {
        $this->authorize('delete', Complaint::find($id));
        $complaint = Complaint::findOrFail($id);

        try{
            Complaint::destroy($id);
            CustomLog::info('Reklamacija "'.$complaint->name.'" uklonjena. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), \Auth::user()->currentTeam->name);
            return back()->with('status', 'Reklamacija je uspešno obrisana');
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja reklamacije "'.$complaint->name.'". Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s').' Greška- '.$e->getMessage(), \Auth::user()->currentTeam->name);
            return back()->with('status', 'Došlo je do greške! Pokušajte ponovo.');
        }
    }
}
