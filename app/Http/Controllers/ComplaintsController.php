<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Complaint;
use App\Facades\CustomLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreComplaintRequest;
use App\Http\Requests\UpdateComplaintRequest;

class ComplaintsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $standardId = $this::getStandard();
        if($standardId == null){
            return redirect('/');
        }

        $complaints = Complaint::where([ ['standard_id', $standardId], ['team_id',Auth::user()->current_team_id] ])->get();
        return view('system_processes.complaints.index', compact('complaints'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Complaint::class);
        return view('system_processes.complaints.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreComplaintRequest $request)
    {

        $this->authorize('create', Complaint::class);

        $complaint = new Complaint();

        $complaint->standard_id = $this::getStandard();
        $complaint->name = $request->name;
        $complaint->description = $request->description;
        $complaint->submission_date = date('Y-m-d', strtotime($request->submission_date));
        $complaint->process = $request->process;
        $complaint->accepted = $request->accepted;
        $complaint->status = $request->status != null ? $request->status : 1;
        $complaint->responsible_person = $request->responsible_person;
        $complaint->way_of_solving = $request->way_of_solving;
        $complaint->deadline_date = date('Y-m-d', strtotime($request->deadline_date));
        $complaint->closing_date = $request->status == 1 ? date('Y-m-d') : null;

        $complaint->user_id = Auth::user()->id;
        $complaint->team_id = Auth::user()->current_team_id;
        try{
        $complaint->save();
            CustomLog::info('Reklamacija "'.$complaint->name.'" kreirana. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
            $request->session()->flash('status', 'Reklamacija je uspešno sačuvana!');
        }catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja reklamacije. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s').' Greška- '.$e->getMessage(), 'Firma-'.\Auth::user()->current_team_id);
            $request->session()->flash('status', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/complaints');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $complaint = Complaint::findOrFail($id);
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('update', Complaint::find($id));

        $complaint = Complaint::findOrFail($id);
        return view('system_processes.complaints.edit', compact('complaint'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateComplaintRequest $request, $id)
    {
        $this->authorize('update', Complaint::find($id));

        $complaint = Complaint::findOrFail($id);

        $complaint->name = $request->name;
        $complaint->description = $request->description;
        $complaint->submission_date = date('Y-m-d', strtotime($request->submission_date));
        $complaint->process = $request->process;
        $complaint->accepted = $request->accepted;
        $complaint->status = $request->status != null ? $request->status : 1;
        $complaint->responsible_person = $request->responsible_person;
        $complaint->way_of_solving = $request->way_of_solving;
        $complaint->deadline_date = date('Y-m-d', strtotime($request->deadline_date));
        $complaint->closing_date = $request->status == 1 ? date('Y-m-d') : null;
        try{
            $complaint->save();
            CustomLog::info('Reklamacija "'.$complaint->name.'" izmenjena. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
            $request->session()->flash('status', 'Reklamacija je uspešno izmenjena!');
        }catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene reklamacije. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s').' Greška- '.$e->getMessage(), 'Firma-'.\Auth::user()->current_team_id);
            $request->session()->flash('status', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/complaints');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete', Complaint::find($id));
        $complaint = Complaint::findOrFail($id);

        if(Complaint::destroy($id)){
            CustomLog::info('Reklamacija "'.$complaint->name.'" uklonjena. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
            return back()->with('status', 'Reklamacija je uspešno obrisana');
        }else{
            return back()->with('status', 'Došlo je do greške! Pokušajte ponovo.');
        }
    }
}
