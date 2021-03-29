<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Accident;
use Illuminate\Support\Str;
use App\Facades\CustomLog;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AccidentRequest;
use App\Exports\AccidentsExport;
use Maatwebsite\Excel\Facades\Excel;

class AccidentController extends Controller
{

    public function index(){

        if(session('standard') == null || session('standard_name') != "45001"){
            return redirect('/');
        }

        $accidents = Accident::where([
            ['standard_id', session('standard')],
            ['team_id', Auth::user()->current_team_id]
        ])->get();

        return view('system_processes.accident.index',['accidents'=>$accidents]);
    }

    public function show($id){
        $accident = Accident::with('user')->findOrFail($id);
        $this->authorize('view', $accident);
        echo $accident;
    }

    public function create(){
        if(session('standard') == null || session('standard_name') != "45001"){
            return redirect('/');
        }
        $this->authorize('create', Accident::class);
        return view('system_processes.accident.create');
    }

    public function store(AccidentRequest $request){

        $this->authorize('create', Accident::class);
        try{
            $accident = Accident::create($request->all());
            CustomLog::info('Istraživanje incidenta za radnika "'.$accident->name.'"je kreirano, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', __('Istraživanje incidenta je uspešno sačuvano!')));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja istraživanja incidenta, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška- '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', __('Došlo je do greške, pokušajte ponovo!')));
        }
        return redirect('/accidents');
    }

    public function edit($id){
        $accident = Accident::findOrFail($id);
        $this->authorize('update', $accident);
        return view('system_processes.accident.edit',['accident'=>$accident]);
    }

    public function update(AccidentRequest $request,$id){
        $accident = Accident::findOrFail($id);
        $this->authorize('update', $accident);

        try{
            $accident->update($request->all());
            CustomLog::info('Istraživanje incidenta za radnika "'.$accident->name.'" izmenjeno, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', __('Istraživanje incidenta je uspešno izmenjeno!')));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene istraživanja incidenta za radnika "'.$accident->name.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška- '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', __('Došlo je do greške, pokušajte ponovo!')));
        }
        return redirect('/accidents');
    }

    public function destroy($id){
        $accident = Accident::findOrFail($id);
        $this->authorize('delete', $accident);

        try{
            Accident::destroy($id);
            CustomLog::info('Istraživanje incidenta za radnika "'.$accident->name.'" je uklonjeno, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', __('Istraživanje incidenta je uspešno obrisano')));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja istraživanja incidenta za radnika "'.$accident->name.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška- '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', __('Došlo je do greške! Pokušajte ponovo.')));
        }
    }

    public function export()
    {
        if(empty(session('standard'))){
            return redirect('/');
        }
        return Excel::download(new AccidentsExport, Str::snake(__('Istraživanje incidenata')).'_'.session('standard_name').'.xlsx');
    }

    public function print($id)
    {
        $accident = Accident::with('user','standard')->findOrFail($id);
        $this->authorize('view', $accident);
        return view('system_processes.accident.print', compact('accident'));

    }
}
