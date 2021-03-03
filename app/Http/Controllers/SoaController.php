<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Soa;
use App\Models\SoaField;
use App\Models\SoaFieldGroup;
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
        $soas = Soa::where('team_id',Auth::user()->current_team_id)->with('soaField')->get();
        $groups = SoaFieldGroup::all();
        return view('system_processes.statement_of_applicability.index',['soas'=>$soas, 'groups' => $groups]);
    }

    public function create()
    {
        //$this->authorize('create', Soa::class);
        $fields = SoaField::all();
        $groups = SoaFieldGroup::all();
        return view('system_processes.statement_of_applicability.create', compact('fields', 'groups'));
    }

    public function store(Request $request)
    {
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

            CustomLog::info('Izjava o primenljivosti dodata, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', 'Izjava o primenljivosti je uspešno sačuvana!'));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja izjave o primenljivosti, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }
        return redirect('/statement-of-applicability');
    }

    public function show($id)
    {
        //$soa = Soa::findOrFail($id);
        //return response()->json($soa);
    }

    public function edit($teamId){
        $this->authorize('create', Soa::class);
        $groups = SoaFieldGroup::all();
        $fields = Soa::where('team_id', $teamId)->with('soaField')->get();
        return View('system_processes.statement_of_applicability.edit', compact('fields', 'groups'));
    }

    public function update(Request $request, $id)
    {
        $this->authorize('create', Soa::class);

        DB::transaction(function () use($request) {
            foreach($request->except(['_token', '_method']) as $key => $req){
                Soa::where('id', $key)->update([
                    'comment' => $req['comment'],
                    'status' => $req['status']
                ]);
            }
        }, 5);

        try{
            CustomLog::info('Izjava o primenljivosti izmenjena, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', 'Izjava o primenljivosti je uspešno izmenjena!'));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene izjave o primenljivosti, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }
        return redirect('/statement-of-applicability');
    }

    public function destroy($id)
    {
        //
    }

}
