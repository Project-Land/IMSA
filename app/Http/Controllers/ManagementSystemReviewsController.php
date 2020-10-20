<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ManagementSystemReview;

class ManagementSystemReviewsController extends Controller
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
        $msr = ManagementSystemReview::where('standard_id', $standardId)->get();
        return view('system_processes.management_system_reviews.index', compact('msr'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('system_processes.management_system_reviews.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $msr = new ManagementSystemReview();

        $messages = array(
            'participants.required' => 'Unesite učesnike',
            'measures_status.required' => 'Unesite status mera',
            'internal_external_changes.required' => 'Unesite promene',
            'customer_satisfaction.required' => 'Unesite zadovoljstvo klijenata',
            'monitoring_measurement_results.required' => 'Unesite rezultate praćenja merenja',
            'resource_adequacy.required' => 'Unesite adekvatnost resursa',
            'improvement_opportunities.required' => 'Unesite prilike za poboljšanje',
            'needs_for_change.required' => 'Unesite potrebe za izmenama u sistemu menadžmenta',
            'needs_for_resources.required' => 'Unesite potrebe za resursima',

        );

        $validatedData = $request->validate([
            'participants' => 'required',
            'measures_status' => 'required',
            'internal_external_changes' => 'required',
            'monitoring_measurement_results' => 'required',
            'customer_satisfaction' => 'required',
            'resource_adequacy' => 'required',
            'improvement_opportunities' => 'required',
            'needs_for_change' => 'required',
            'needs_for_resources' => 'required',

        ], $messages);

        //save
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $msr = ManagementSystemReview::findOrFail($id);
        return response()->json($msr);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $msr = ManagementSystemReview::findOrFail($id);
        return view('system_processes.management_system_reviews.edit', compact('msr'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $msr = ManagementSystemReview::findOrFail($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        if(ManagementSystemReview::destroy($id)){
            return back()->with('status', 'Zapisnik je uspešno uklonjen');
        }else{
            return back()->with('status', 'Došlo je do greške! Pokušajte ponovo.');
        }
    }
}
