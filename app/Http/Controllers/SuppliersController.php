<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use App\Facades\CustomLog;
use App\Models\Notification;
use App\Http\Requests\SuppliersRequest;

class SuppliersController extends Controller
{

    public function index()
    {
        if(request()->has('standard') && request()->has('standard_name')){
            session(['standard' => request()->get('standard')]);
            session(['standard_name' => request()->get('standard_name')]);
        }

        if(session('standard') == null){
            return redirect('/')->with('status', array('secondary', 'Izaberite standard!'));
        }

        $suppliers = Supplier::where([
                ['standard_id', session('standard')],
                ['team_id', Auth::user()->current_team_id]
            ])->get();

        return view('system_processes.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        $this->authorize('create', Supplier::class);
        return view('system_processes.suppliers.create');
    }

    public function store(SuppliersRequest $request)
    {
        $this->authorize('create', Supplier::class);

        try{
            $supplier = Supplier::create($request->all());

            $notification = Notification::create([
                'message' => 'Preispitivanje isporučilaca za '.date('d.m.Y', strtotime($supplier->deadline_date)),
                'team_id' => Auth::user()->current_team_id,
                'checkTime' => $supplier->deadline_date
            ]);

            $supplier->notification()->save($notification);

            CustomLog::info('Isporučilac "'.$supplier->supplier_name.'" kreiran, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', 'Odabrani isporučilac je uspešno sačuvan!'));
        } catch(Exception $e){
            $request->session()->flash('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
            CustomLog::warning('Neuspeli pokušaj kreiranja isporučioca, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
        }

        return redirect('/suppliers');
    }

    public function show(int $id)
    {
        if(!request()->expectsJson()){
            abort(404);
        }

        return response()->json(Supplier::findOrFail($id));
    }

    public function edit($id)
    {
        $this->authorize('update', Supplier::find($id));
        $supplier = Supplier::findOrFail($id);
        return view('system_processes.suppliers.edit', compact('supplier'));
    }

    public function update(SuppliersRequest $request, $id)
    {
        $this->authorize('update', Supplier::find($id));
        $supplier = Supplier::findOrFail($id);

        try{
            $supplier->update($request->all());

            $notification = $supplier->notification;
            if(!$notification){
                $notification=new Notification();
                $notification->team_id=Auth::user()->current_team_id;
            }
            $notification->message = 'Preispitivanje isporučilaca za '.date('d.m.Y', strtotime($supplier->deadline_date));
            $notification->checkTime = $supplier->deadline_date;
            $supplier->notification()->save($notification);

            CustomLog::info('Isporučilac "'.$supplier->supplier_name.'" izmenjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', 'Odabrani isporučilac je uspešno izmenjen!'));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene isporučioca "'.$supplier->supplier_name.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', 'Došlo je do greške, pokušajte ponovo'));
        }
        return redirect('/suppliers');
    }

    public function destroy($id)
    {
        $this->authorize('delete', Supplier::find($id));
        $supplier = Supplier::findOrFail($id);

        try{
            $supplier->notification()->delete();
            Supplier::destroy($id);
            CustomLog::info('Isporučilac "'.$supplier->supplier_name.'" uklonjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', 'Odabrani isporučilac je uspešno uklonjen'));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja isporučioca "'.$supplier->supplier_name.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', 'Došlo je do greške! Pokušajte ponovo.'));
        }
    }
}
