<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $complaints = Complaint::where([['standard_id', $standardId],['team_id',Auth::user()->current_team_id]])->get();
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
    public function store(Request $request)
    {

        $this->authorize('create', Complaint::class);

        $complaint = new Complaint();

        $messages = array(
            'name.required' => 'Unesite oznaku reklamacije',
            'name.max' => 'Oznaka može sadržati najviše 190 karaktera',
            'description.required' => 'Unesite opis reklamacije',
            'submission_date.required' => 'Unesite datum podnošenja reklamacije',
            'process.required' => 'Unesite proces na koji se reklamacija odnosi',
            'process.max' => 'Polje može sadržati najviše 190 karaktera',
            'responsible_person.max' => 'Polje može sadržati najviše 190 karaktera',
            'way_of_solving.max' => 'Polje može sadržati najviše 190 karaktera'
        );

        $validatedData = $request->validate([
            'name' => 'required|max:190',
            'description' => 'required',
            'submission_date' => 'required',
            'process' => 'required',
            'responsible_person' => 'max:190',
            'way_of_solving' => 'max:190'
        ], $messages);

        $complaint->standard_id = $this::getStandard();
        $complaint->name = $request->name;
        $complaint->description = $request->description;
        $complaint->submission_date = $request->submission_date;
        $complaint->process = $request->process;
        $complaint->accepted = $request->accepted;
        $complaint->status = $request->status;
        $complaint->responsible_person = $request->responsible_person;
        $complaint->way_of_solving = $request->way_of_solving;
        $complaint->deadline_date = $request->deadline_date;

        $complaint->save();
        $request->session()->flash('status', 'Reklamacija je uspešno sačuvana!');
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
    public function update(Request $request, $id)
    {
        $this->authorize('update', Complaint::find($id));

        $complaint = Complaint::findOrFail($id);

        $messages = array(
            'name.required' => 'Unesite oznaku reklamacije',
            'name.max' => 'Oznaka može sadržati najviše 190 karaktera',
            'desription.required' => 'Unesite opis reklamacije',
            'submission_date.required' => 'Unesite datum podnošenja reklamacije',
            'process.required' => 'Unesite proces na koji se reklamacija odnosi',
            'process.max' => 'Polje može sadržati najviše 190 karaktera',
            'responsible_person.max' => 'Polje može sadržati najviše 190 karaktera',
            'way_of_solving.max' => 'Polje može sadržati najviše 190 karaktera'
        );

        $validatedData = $request->validate([
            'name' => 'required|max:190',
            'description' => 'required',
            'submission_date' => 'required',
            'process' => 'required',
            'responsible_person' => 'max:190',
            'way_of_solving' => 'max:190'
        ], $messages);

        $complaint->name = $request->name;
        $complaint->description = $request->description;
        $complaint->submission_date = $request->submission_date;
        $complaint->process = $request->process;
        $complaint->accepted = $request->accepted;
        $complaint->status = $request->status;
        $complaint->responsible_person = $request->responsible_person;
        $complaint->way_of_solving = $request->way_of_solving;
        $complaint->deadline_date = $request->deadline_date;

        $complaint->save();
        $request->session()->flash('status', 'Reklamacija je uspešno izmenjena!');
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

        if(Complaint::destroy($id)){
            return back()->with('status', 'Reklamacija je uspešno obrisana');
        }else{
            return back()->with('status', 'Došlo je do greške! Pokušajte ponovo.');
        }
    }
}
