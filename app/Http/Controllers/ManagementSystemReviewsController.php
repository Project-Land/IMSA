<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ManagementSystemReview;
use App\Models\Goal;
use App\Models\CorrectiveMeasure;
use App\Models\PlanIp;
use App\Models\Supplier;
use App\Models\RiskManagement;
use App\Http\Requests\StoreManagementSystemReview;

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
    public function store(StoreManagementSystemReview $request)
    {
        $msr = new ManagementSystemReview();

        $year = (int)$request->year;
        $standardId = $this::getStandard();

        $msr->standard_id = $this::getStandard();
        $msr->year = $request->year;
        $msr->participants = $request->participants;
        $msr->measures_status = $request->measures_status;
        $msr->internal_external_changes = $request->internal_external_changes;
        $msr->customer_satisfaction = $request->customer_satisfaction;
        $msr->monitoring_measurement_results = $request->monitoring_measurement_results;
        $msr->resource_adequacy = $request->resource_adequacy;
        $msr->resource_adequacy = $request->resource_adequacy;
        $msr->checks_results_desc = $request->checks_results_desc;
        $msr->improvement_opportunities = $request->improvement_opportunities;
        $msr->needs_for_change = $request->needs_for_change;
        $msr->needs_for_resources = $request->needs_for_resources;

        $msr->objectives_scope = Goal::getStats($standardId, $year);
        $msr->inconsistancies_corrective_measures = CorrectiveMeasure::getStats($standardId, $year);
        $msr->checks_results = PlanIp::getStats($standardId, $year);
        $msr->external_suppliers_performance = Supplier::getStats($standardId, $year);
        $msr->measures_effectiveness = RiskManagement::getStats($standardId, $year);    
        
        $msr->save();

        $request->session()->flash('status', 'Zapisnik je uspešno sačuvan!');
        return redirect('/management-system-reviews');
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

        $messages = array(
            'participants.required' => 'Unesite učesnike',
            'measures_status.required' => 'Unesite status mera',
            'internal_external_changes.required' => 'Unesite promene',
            'customer_satisfaction.required' => 'Unesite zadovoljstvo klijenata',
            'monitoring_measurement_results.required' => 'Unesite rezultate praćenja merenja',
            'resource_adequacy.required' => 'Unesite adekvatnost resursa'
        );

        $validatedData = $request->validate([
            'participants' => 'required',
            'measures_status' => 'required',
            'internal_external_changes' => 'required',
            'monitoring_measurement_results' => 'required',
            'customer_satisfaction' => 'required',
            'resource_adequacy' => 'required'
        ], $messages);

        $msr->year = $request->year;
        $msr->participants = $request->participants;
        $msr->measures_status = $request->measures_status;
        $msr->internal_external_changes = $request->internal_external_changes;
        $msr->customer_satisfaction = $request->customer_satisfaction;
        $msr->monitoring_measurement_results = $request->monitoring_measurement_results;
        $msr->resource_adequacy = $request->resource_adequacy;
        $msr->resource_adequacy = $request->resource_adequacy;
        $msr->checks_results_desc = $request->checks_results_desc;
        $msr->improvement_opportunities = $request->improvement_opportunities;
        $msr->needs_for_change = $request->needs_for_change;
        $msr->needs_for_resources = $request->needs_for_resources;

        $year = (int)$request->year;
        $standardId = $this::getStandard();

        $msr->objectives_scope = Goal::getStats($standardId, $year);
        $msr->inconsistancies_corrective_measures = CorrectiveMeasure::getStats($standardId, $year);
        $msr->checks_results = PlanIp::getStats($standardId, $year);
        $msr->external_suppliers_performance = Supplier::getStats($standardId, $year);
        $msr->measures_effectiveness = RiskManagement::getStats($standardId, $year);    
        
        $msr->save();
        $request->session()->flash('status', 'Zapisnik je uspešno izmenjen!');
        return redirect('/management-system-reviews');
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
