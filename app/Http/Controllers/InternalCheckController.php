<?php

namespace App\Http\Controllers;


use App\Models\PlanIp;
use Illuminate\Http\Request;
use App\Models\InternalCheck;
use App\Models\Team;
use Faker\Provider\ar_JO\Internet;

class InternalCheckController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $internal_checks=InternalCheck::all();
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
        return view('system_processes.internal_check.create');
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
            'sector' => 'required',
            'leaders' => 'required',
            'standard_id' => 'required',
        ]);
        $internalCheck=InternalCheck::create($validatedData);
       $planIp=new PlanIp();
       $planIp->save();
       $planIp->name=$planIp->id.'/'.date('Y');
       $planIp->save();
        $planIp->internalCheck()->save($internalCheck);
        $request->session()->flash('status', 'Godišnji plan je uspešno kreiran!');
        
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
            'sector' => 'required',
            'leaders' => 'required',
            'standard_id' => 'required',
            'date'=> 'required|date'
        ]);
        
      
        $internal_check->update($validatedData);
        
        $request->session()->flash('status', 'Godišnji plan je uspešno izmenjen!');
        
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
        InternalCheck::destroy($id);
        return back()->with('status', 'Godišnji plan je uspešno uklonjen');
    }
}
