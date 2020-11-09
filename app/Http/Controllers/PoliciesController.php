<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\PolicyRequest;
use App\Facades\CustomLog;
use Exception;

class PoliciesController extends Controller
{
    public function index()
    {
        $standardId = $this::getStandard();
        if($standardId == null){
            return redirect('/')->with('status', 'Izaberite standard!');
        }

        $documents = Document::where([
                ['doc_category', 'policy'],
                ['standard_id', $standardId],
                ['team_id', Auth::user()->current_team_id]
            ])->get();
            
        $folder = \Str::snake($this::getCompanyName())."/policy";
        $route_name = "policies";
        $doc_type="Politike";
        return view('documents.index', compact('documents', 'folder', 'route_name','doc_type'));
    }

    public function create()
    {
        $this->authorize('create', Document::class);
        return view('documents.create',
            [
                'url' => route('policies.store'),
                'back' => route('policies.index'),
                'doc_type'=>'Politike'
            ]
        );
    }

    public function store(PolicyRequest $request)
    {
        $this->authorize('create', Document::class);
        $document = new Document();
        $upload_path = "/public/".\Str::snake($this::getCompanyName())."/policy";

        try{
            $document = Document::create($request->except('file'));
            Storage::putFileAs($upload_path, $request->file, $request->file_name);
            CustomLog::info('Dokument Politike "'.$document->document_name.'" kreiran, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Dokument je uspešno sačuvan!');
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja dokumenta Politike "'.$document->document_name.'", '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Došlo je do greške! Pokušajte ponovo.');
        }
        
        return redirect('/policies');
    }

    public function show($id)
    {
        $document = Document::findOrFail($id);
        abort(404);
    }

    public function edit($id)
    {
        $this->authorize('update', Document::find($id));
        $document = Document::findOrFail($id);
        return view('documents.edit',
            [
                'document' => $document,
                'url' => route('policies.update', $document->id),
                'folder' => 'policy',
                'back' => route('policies.index'),
                'doc_type'=>'Politike'
            ]
        );
    }

    public function update(PolicyRequest $request, $id)
    {
        $this->authorize('update', Document::find($id));
        $document = Document::findOrFail($id);

        try{
            if($request->file){
                $upload_path = "/public/".\Str::snake($this::getCompanyName())."/policy";
                Storage::putFileAs($upload_path, $request->file, $request->file_name);
            }
            $document->update($request->except('file'));
            $request->session()->flash('status', 'Dokument je uspešno izmenjen!');
            CustomLog::info('Dokument Politike "'.$document->document_name.'" izmenjen, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene dokumenta Politike '.$document->document_name.', '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s').' Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Došlo je do greške! Pokušajte ponovo.');
        }
        return redirect('/policies');
    }

    public function destroy($id)
    {
        $this->authorize('delete', Document::find($id));
        $document_name = Document::find($id)->document_name;
        
        try{
            Document::destroy($id);
            CustomLog::info('Dokument Politike "'.$document_name.'" uklonjen, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
            return back()->with('status', 'Dokument je uspešno uklonjen');
        }catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja dokumenta Politike '.$document_name.', '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s').' Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
            return back()->with('status', 'Došlo je do greške! Pokušajte ponovo.');
        }
    }
}
