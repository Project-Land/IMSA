<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Soa;
use App\Models\SoaField;
use App\Models\SoaFieldGroup;
use App\Models\Document;
use App\Facades\CustomLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SoaController extends Controller
{

    public function index()
    {
        if(empty(session('standard')) || session('standard_name') != "27001"){
            return redirect('/');
        }

        $soas = Soa::where([
            ['standard_id', session('standard')],
            ['team_id', Auth::user()->current_team_id]
        ])->with('soaField', 'documents')->get();

        $groups = SoaFieldGroup::all();
        return view('system_processes.statement_of_applicability.index', ['soas' => $soas, 'groups' => $groups]);
    }

    public function create()
    {
        if(empty(session('standard')) || session('standard_name') != "27001"){
            return redirect('/');
        }
        $this->authorize('create', Soa::class);
        $fields = SoaField::all();
        $groups = SoaFieldGroup::all();

        $documents = Document::where([
            ['doc_category', 'policy'],
            ['standard_id', session('standard')],
            ['team_id', Auth::user()->current_team_id]
        ])->orWhere(function($query){
                $query->where('doc_category', 'procedure')
                ->where('standard_id', session('standard'))
                ->where('team_id', Auth::user()->current_team_id);
            })
            ->get();

        return view('system_processes.statement_of_applicability.create', compact('fields', 'groups', 'documents'));
    }

    public function store(Request $request)
    {
      //  $validated = $request->validate([
      //      '*.comment' => 'required'
      //  ]);
        $this->authorize('create', Soa::class);
        try{
            DB::transaction(function () use($request) {

                foreach($request->except(['_token', '_method']) as $key => $req){
                    if(!empty($req)){
                        $soa = Soa::create([
                            'comment' => $req['comment']?? "",
                            'status' => $req['status']?? "",
                            'soa_field_id' => $key,
                            'user_id' => Auth::user()->id,
                            'team_id' => Auth::user()->current_team_id,
                            'standard_id' => session('standard'),
                        ]);

                        $soa->documents()->sync($req['document'] ?? []);
                    }
                }

            }, 5);

            CustomLog::info('Izjava o primenjivosti dodata, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', 'Izjava o primenljivosti je uspešno sačuvana!'));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja izjave o primenjivosti, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }
        return redirect('/statement-of-applicability');
    }

    public function show($id)
    {
        abort('404');
    }

    public function edit($teamId){
        $this->authorize('update', Soa::class);
        $groups = SoaFieldGroup::all();
        $fields = Soa::where('team_id', $teamId)->with('soaField', 'documents')->get();
        $alldocuments = Document::where([
            ['doc_category', 'policy'],
            ['standard_id', session('standard')],
            ['team_id', Auth::user()->current_team_id]
        ])->orWhere(function($query){
                $query->where('doc_category', 'procedure')
                ->where('standard_id', session('standard'))
                ->where('team_id', Auth::user()->current_team_id);
            })
            ->get();
        return View('system_processes.statement_of_applicability.edit', compact('fields', 'groups', 'alldocuments'));
    }

    public function update(Request $request, $id)
    {
        $this->authorize('update', Soa::class);

        DB::transaction(function () use($request) {
            foreach($request->except(['_token', '_method']) as $key => $req){
                $soa = Soa::find($key);
                $soa->update([
                    'comment' => $req['comment'],
                    'status' => $req['status']
                ]);

                //$documents = $soa->documents;
                $formDocuments = $req['document'] ?? [];

                $soa->documents()->sync($formDocuments);
            }
        }, 5);

        try{
            CustomLog::info('Izjava o primenjivosti izmenjena, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', 'Izjava o primenljivosti je uspešno izmenjena!'));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene izjave o primenjivosti, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }
        return redirect('/statement-of-applicability');
    }

    public function destroy($id)
    {
        //
    }

}
