<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use \Carbon\Carbon;

class SuppliersController extends Controller
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
        $suppliers = Supplier::where('standard_id', $this::getStandard())->get();
        return view('system_processes.suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('system_processes.suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $supplier = new Supplier();

        $messages = array(
            'supplier_name.required' => 'Unesite naziv isporučioca',
            'name.max' => 'Naziv može sadržati maksimalno 190 karaktera',
            'subject.required' => 'Unesite predmet nabavke'
        );

        $validatedData = $request->validate([
            'supplier_name' => 'required|max:190',
            'subject' => 'required'
        ], $messages);

        $supplier->standard_id = $this::getStandard();
        $supplier->supplier_name = $request->supplier_name;
        $supplier->subject = $request->subject;
        $supplier->quality = $request->quality;
        $supplier->price = $request->price;
        $supplier->shippment_deadline = $request->shippment_deadline;

        $total = $supplier->quality + $supplier->price + $supplier->shippment_deadline;
        $supplier->status = $total >= 8.5 ? 1:0;

        $newDateTime = Carbon::parse(Carbon::now()->toDateTimeString())->addMonths(6);
        $supplier->deadline_date = $newDateTime;

        $supplier->save();
        $request->session()->flash('status', 'Odabrani isporučilac je uspešno sačuvan!');
        return redirect('/suppliers');
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
        $supplier = Supplier::findOrFail($id);
        return view('system_processes.suppliers.edit', compact('supplier'));
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
        $supplier = Supplier::findOrFail($id);
        
        $messages = array(
            'supplier_name.required' => 'Unesite naziv isporučioca',
            'name.max' => 'Naziv može sadržati maksimalno 190 karaktera',
            'subject.required' => 'Unesite predmet nabavke'
        );

        $validatedData = $request->validate([
            'supplier_name' => 'required|max:190',
            'subject' => 'required'
        ], $messages);

        $supplier->supplier_name = $request->supplier_name;
        $supplier->subject = $request->subject;
        $supplier->quality = $request->quality;
        $supplier->price = $request->price;

        $total = $supplier->quality + $supplier->price + $supplier->shippment_deadline;
        $supplier->status = $total >= 8.5 ? 1:0;

        $newDateTime = Carbon::parse(Carbon::now()->toDateTimeString())->addMonths(6);
        $supplier->deadline_date = $newDateTime;

        $supplier->save();
        $request->session()->flash('status', 'Odabrani isporučilac je uspešno izmenjen!');
        return redirect('/suppliers');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Supplier::destroy($id);
        return back()->with('status', 'Odobreni isporučilac je uspešno uklonjen');
    }
}
