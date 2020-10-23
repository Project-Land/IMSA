<?php

namespace App\Http\Controllers;

use App\Models\Stakeholder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StakeholdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        if($this::getStandard() == null){
            return redirect('/');
        }
        $stakeholders = Stakeholder::where([['standard_id', $this::getStandard()],['team_id',Auth::user()->current_team_id]])->get();
        return view('system_processes.stakeholders.index', compact('stakeholders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Stakeholder::class);

        return view('system_processes.stakeholders.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Stakeholder::class);
        
        $messages = array(
            'name.required' => 'Unesite naziv / ime zainteresovane strane',
            'name.max' => 'Naziv može sadržati maksimalno 190 karaktera',
            'expectation.required' => 'Unesite očekivanja',
            'response.required' => 'Unesite odgovor'
        );

        $validatedData = $request->validate([
            'name' => 'required|max:190',
            'expectation' => 'required',
            'response' => 'required'
        ], $messages);

        $stakeholder = Stakeholder::create([
            'name' => $request->name,
            'standard_id' => $this::getStandard(),
            'expectation' => $request->expectation,
            'response' => $request->response
        ]);

        $request->session()->flash('status', 'Zainteresovana strana je uspešno sačuvana!');
        return redirect('/stakeholders');
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
        $this->authorize('update', Stakeholder::find($id));

        $stakeholder = Stakeholder::findOrFail($id);
        return view('system_processes.stakeholders.edit', compact('stakeholder'));
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
        $this->authorize('update', Stakeholder::find($id));

        $stakeholder = Stakeholder::findOrFail($id);

        $messages = array(
            'name.required' => 'Unesite naziv / ime zainteresovane strane',
            'name.max' => 'Naziv može sadržati maksimalno 190 karaktera',
            'expectation.required' => 'Unesite očekivanja',
            'response.required' => 'Unesite odgovor'
        );

        $validatedData = $request->validate([
            'name' => 'required|max:190',
            'expectation' => 'required',
            'response' => 'required'
        ], $messages);

        $stakeholder->standard_id = $this::getStandard();
        $stakeholder->name = $request->name;
        $stakeholder->expectation = $request->expectation;
        $stakeholder->response = $request->response;

        $stakeholder->save();

        $request->session()->flash('status', 'Zainteresovana strana je uspešno izmenjena!');
        return redirect('/stakeholders');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete', Stakeholder::find($id));

        if(Stakeholder::destroy($id)){
            return back()->with('status', 'Zainteresovana strana je uspešno uklonjena');
        }else{
            return back()->with('status', 'Došlo je do greške! Pokušajte ponovo.');
        }
    }
}
