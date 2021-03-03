<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Soa;
use App\Facades\CustomLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SoaController extends Controller
{

    public function index()
    {
        if(session('standard') == null){
            return redirect('/')->with('status', array('secondary', 'Izaberite standard!'));
        }
        $soas=Soa::where('team_id',Auth::user()->current_team_id);
        return view('system_processes.statement_of_applicability.index',['soas'=>$soas]);
    }

    public function create()
    {
        $this->authorize('create', Soa::class);
        return view('system_processes.statement_of_applicability.create');
    }

    public function store(Request $request)
    {
        dd($request->all());
        $this->authorize('create', Soa::class);
        try{

            DB::transaction(function () use($request) {

                foreach($request->except(['_token', '_method']) as $key => $req){
                    Soa::where('id', $key)->update([
                        'comment' => $req['comment'],
                        'status' => $req['status']
                    ]);
                }

            }, 5);

            CustomLog::info('Soa dodat, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', 'Plan je uspešno sačuvan!'));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja soa, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }
        return redirect('/statement-of-applicability');
    }

    public function show($id)
    {
        $soa = Soa::findOrFail($id);
        return response()->json($soa);
    }

    public function edit($teamId){
        $fields = Soa::where('team_id', $teamId)->get();
        return View('system_processes.statement_of_applicability.edit', compact('fields'));
    }

    public function update(Request $request, $id)
    {
        DB::transaction(function () use($request) {
            foreach($request->except(['_token', '_method']) as $key => $req){
                Soa::where('id', $key)->update([
                    'comment' => $req['comment'],
                    'status' => $req['status']
                ]);
            }
        }, 5);

        try{
            CustomLog::info('Soa izmenjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', 'Plan je uspešno izmenjen!'));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene soa, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }
        return redirect('/statement-of-applicability');
    }

    public function destroy($id)
    {
        $this->authorize('delete', Soa::find($id));
        $risk = Soa::findOrFail($id);

        try{

            CustomLog::info('Soa uklonjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', 'Plan je uspešno uklonjen'));
        }
        catch (Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja soa, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }
    }

}
