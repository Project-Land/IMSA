<?php

namespace App\Http\Controllers;

use \Carbon\Carbon;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Facades\CustomLog;
use App\Http\Requests\StoreSuppliersRequest;
use App\Http\Requests\UpdateSuppliersRequest;
use App\Models\Notification;
use Exception;

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
        $suppliers = Supplier::where([['standard_id', $this::getStandard()],['team_id',Auth::user()->current_team_id]])->get();
        return view('system_processes.suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Supplier::class);
        return view('system_processes.suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSuppliersRequest $request)
    {
        $this->authorize('create', Supplier::class);
        $supplier = new Supplier();

        $supplier->standard_id = $this::getStandard();
        $supplier->supplier_name = $request->supplier_name;
        $supplier->subject = $request->subject;
        $supplier->quality = $request->quality;
        $supplier->price = $request->price;
        $supplier->shippment_deadline = $request->shippment_deadline;
        $supplier->personal_info = $request->personal_info;
        $supplier->phone_number = $request->phone_number;
        $supplier->email = $request->email;

        $total = $supplier->quality + $supplier->price + $supplier->shippment_deadline;
        $supplier->status = $total >= 8.5 ? 1:0;

        $newDateTime = Carbon::parse(Carbon::now()->toDateTimeString())->addMonths(6);
        $supplier->deadline_date = $newDateTime;

        $supplier->team_id = Auth::user()->current_team_id;
     //   $supplier->user_id = Auth::user()->id;
    try{
        $supplier->save();
        $notification=Notification::create([
            'message'=>'Datum sledećeg preispitivanja '.$supplier->deadline_date->format('d.m.Y'),
            'team_id'=>Auth::user()->current_team_id,
            'checkTime' => $supplier->deadline_date
        ]);
        $supplier->notification()->save($notification);
        CustomLog::info('Isporučilac "'.$supplier->supplier_name.'" kreiran. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
        $request->session()->flash('status', 'Odabrani isporučilac je uspešno sačuvan!');
       
    }catch(Exception $e){
        $request->session()->flash('status', 'Došlo je do greške, pokušajte ponovo!');
        CustomLog::warning('Neuspeli pokušaj kreiranja dobavljača. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s').' Greška- '.$e->getMessage(), 'Firma-'.\Auth::user()->current_team_id);
    }
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
        $supplier = Supplier::findOrFail($id);
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
        $this->authorize('update', Supplier::find($id));
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
    public function update(UpdateSuppliersRequest $request, $id)
    {
        $this->authorize('update', Supplier::find($id));

        $supplier = Supplier::findOrFail($id);
        
        $supplier->supplier_name = $request->supplier_name;
        $supplier->subject = $request->subject;
        $supplier->quality = $request->quality;
        $supplier->price = $request->price;
        $supplier->personal_info = $request->personal_info;
        $supplier->phone_number = $request->phone_number;
        $supplier->email = $request->email;

        $total = $supplier->quality + $supplier->price + $supplier->shippment_deadline;
        $supplier->status = $total >= 8.5 ? 1:0;

        $newDateTime = Carbon::parse(Carbon::now()->toDateTimeString())->addMonths(6);
        $supplier->deadline_date = $newDateTime;
        try{
            $supplier->save();
            $notification=$supplier->notification;
            $notification->message='Datum sledećeg preispitivanja '.$supplier->deadline_date->format('d.m.Y');
            $notification->checkTime = $supplier->deadline_date;
            $supplier->notification()->save($notification);
            CustomLog::info('Isporučilac "'.$supplier->supplier_name.'" izmenjen. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
            $request->session()->flash('status', 'Odabrani isporučilac je uspešno izmenjen!');
        }catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene dobavljača. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s').' Greška- '.$e->getMessage(), 'Firma-'.\Auth::user()->current_team_id);
            $request->session()->flash('status', 'Došlo je do greške, pokušajte ponovo');
        }
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
        $this->authorize('delete', Supplier::find($id));
        $supplier = Supplier::findOrFail($id);
        if(Supplier::destroy($id)){
            CustomLog::info('Isporučilac "'.$supplier->supplier_name.'" uklonjen. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
            return back()->with('status', 'Odabrani isporučilac je uspešno uklonjen');
        }else{
            return back()->with('status', 'Došlo je do greške! Pokušajte ponovo.');
        }
    }
}
