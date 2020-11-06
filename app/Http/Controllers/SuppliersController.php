<?php

namespace App\Http\Controllers;

use \Carbon\Carbon;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use App\Facades\CustomLog;
use App\Http\Requests\SuppliersRequest;
use App\Models\Notification;
use Exception;

class SuppliersController extends Controller
{

    public function index()
    {
        if($this::getStandard() == null){
            return redirect('/');
        }

        $suppliers = Supplier::where([
                ['standard_id', $this::getStandard()],
                ['team_id',Auth::user()->current_team_id]
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

            CustomLog::info('Isporučilac "'.$supplier->supplier_name.'" kreiran, '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Odabrani isporučilac je uspešno sačuvan!');
        } catch(Exception $e){
            $request->session()->flash('status', 'Došlo je do greške, pokušajte ponovo!');
            CustomLog::warning('Neuspeli pokušaj kreiranja dobavljača, '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y H:i:s').' Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
        }

        return redirect('/suppliers');
    }

    public function show($id)
    {
        $supplier = Supplier::findOrFail($id);
        abort(404);
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
            $notification->message = 'Preispitivanje isporučilaca za '.date('d.m.Y', strtotime($supplier->deadline_date));
            $notification->checkTime = $supplier->deadline_date;
            $supplier->notification()->save($notification);

            CustomLog::info('Isporučilac "'.$supplier->supplier_name.'" izmenjen, '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Odabrani isporučilac je uspešno izmenjen!');
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene dobavljača "'.$supplier->supplier_name.'", '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y H:i:s').' Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Došlo je do greške, pokušajte ponovo');
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
            CustomLog::info('Isporučilac "'.$supplier->supplier_name.'" uklonjen, '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
            return back()->with('status', 'Odabrani isporučilac je uspešno uklonjen');
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja dobavljača "'.$supplier->supplier_name.'", '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y H:i:s').' Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
            return back()->with('status', 'Došlo je do greške! Pokušajte ponovo.');
        }
    }
}
