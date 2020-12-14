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
        if(session('standard') == null){
            return redirect('/')->with('status', array('secondary', 'Izaberite standard!'));
        }

        $me = MeasuringEquipment::where([
                ['standard_id', session('standard')],
                ['team_id', Auth::user()->current_team_id]
            ])->get();

        return view('system_processes.measuring_equipments.index', ['measuring_equipment' => $me]);
    }

    public function create()
    {
        $this->authorize('create', MeasuringEquipment::class);
        return view('system_processes.measuring_equipments.create');
    }

    public function store(MeasuringEquipmentsRequest $request)
    {
        $this->authorize('create', MeasuringEquipment::class);

        try{
            $me = MeasuringEquipment::create($request->all());

            $notification = Notification::create([
                'message' => 'Datum narednog etaloniranja/baždarenja '.date('d.m.Y', strtotime($me->next_calibration_date)),
                'team_id' => Auth::user()->current_team_id,
                'checkTime' => $me->next_calibration_date
            ]);
            $me->notification()->save($notification);

            CustomLog::info('Merna oprema id: "'.$me->id.'" kreirana, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', 'Merna oprema je uspešno kreirana!'));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja merne opreme, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        } finally{
            return redirect('/measuring-equipment');
        }
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

    public function update(MeasuringEquipmentsRequest $request, $id)
    {
        $me = MeasuringEquipment::findOrFail($id);
        $this->authorize('update', $me);

        try{
            $me->update($request->all());

            $notification = $me->notification;
            if(!$notification){
                $notification = new Notification();
                $notification->team_id = Auth::user()->current_team_id;
            }
            $notification->message = 'Datum narednog etaloniranja/baždarenja '. date('d.m.Y', strtotime($me->next_calibration_date));
            $notification->checkTime = $me->next_calibration_date;
            $me->notification()->save($notification);

            CustomLog::info('Merna oprema id: "'.$me->id.'" izmenjena, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', 'Merna oprema je uspešno izmenjena!'));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene merne opreme id: "'.$me->id.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }
        return redirect('/measuring-equipment');
    }

    public function destroy($id)
    {
        $me = MeasuringEquipment::findOrFail($id);
        $this->authorize('delete', $me);

        try{
            $me->notification()->delete();
            $me->delete();
            CustomLog::info('Merna oprema id: "'.$me->id.'" uklonjena, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', 'Merna oprema je uspešno obrisana'));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja merne opreme id: "'.$me->id.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška- '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', 'Došlo je do greške! Pokušajte ponovo.'));
        }
    }
}
