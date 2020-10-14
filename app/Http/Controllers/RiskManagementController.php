<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RiskManagement;

class RiskManagementController extends Controller
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
        $riskManagements = RiskManagement::all();
        return view('system_processes.risk_management.index', compact('riskManagements'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('system_processes.risk_management.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $risk = new RiskManagement();

        $messages = array(
            'description.required' => 'Unesite opis'
        );

        $validatedData = $request->validate([
            'description' => 'required'
        ], $messages);

        $risk->standard_id = $this::getStandard();
        $risk->description = $request->description;
        $risk->probability = $request->probability;
        $risk->frequency = $request->frequency;
        $risk->acceptable = $request->acceptable;
        $risk->total = $request->probability * $request->frequency;

        $risk->save();

        $request->session()->flash('status', 'Dokument je uspešno sačuvan!');
        return redirect('/risk-management');
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
        $risk = RiskManagement::findOrFail($id);
        return view('system_processes.risk_management.edit', compact('risk'));
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
        $risk = RiskManagement::findOrFail($id);

        $messages = array(
            'description.required' => 'Unesite opis'
        );

        $validatedData = $request->validate([
            'description' => 'required'
        ], $messages);

        $risk->standard_id = $this::getStandard();
        $risk->description = $request->description;
        $risk->probability = $request->probability;
        $risk->frequency = $request->frequency;
        $risk->acceptable = $request->acceptable;
        $risk->total = $request->probability * $request->frequency;

        $risk->save();

        $request->session()->flash('status', 'Dokument je uspešno izmenjen!');
        return redirect('/risk-management');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        RiskManagement::destroy($id);
        return back()->with('status', 'Rizik / plan je uspešno uklonjen');
    }
}
