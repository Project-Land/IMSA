<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\MeasuringEquipment;
use App\Facades\CustomLog;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\MeasuringEquipmentsRequest;

class MeasuringEquipmentsController extends Controller
{

    public function index()
    {
        $standardId = session('standard');
        if($standardId == null){
            return redirect('/')->with('status', 'Izaberite standard!');
        }

        $me = MeasuringEquipment::where([
                ['standard_id', $standardId],
                ['team_id', Auth::user()->current_team_id]
            ])->get();

        return view('system_processes.measuring_equipments.index', ['measuring_equipment' => $me]);
    }

    public function create()
    {
        $this->authorize('create', MeasuringEquipment::class);
        return view('system_processes.measuring_equipments.create');
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        abort(404);
    }

    public function edit($id)
    {
        $me = MeasuringEquipment::findOrFail($id);
        $this->authorize('update', $me);
        return view('system_processes.measuring_equipments.edit', ['measuring_equipment' => $me]);
    }

    public function update(Request $request, $id)
    {
        $me = MeasuringEquipment::findOrFail($id);
        $this->authorize('update', $me);

        try{
            CustomLog::info('Merna oprema "'.$me->name.'" izmenjena, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Merna oprema je uspešno izmenjena!');
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene merne opreme "'.$me->name.'", '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
            $request->session()->flash('warning', 'Došlo je do greške, pokušajte ponovo!');
        }
    }

    public function destroy($id)
    {
        //
    }
}