<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Facades\CustomLog;
use App\Http\Requests\RulesOfProcedureRequest;
use Exception;

class RulesOfProceduresController extends Controller
{
    public function index()
    {
        $standardId = session('standard');
        if($standardId == null){
            return redirect('/')->with('status', 'Izaberite standard!');
        }
        $documents = Document::where([
                ['doc_category', 'rules_procedure'],
                ['standard_id', $standardId],
                ['team_id', Auth::user()->current_team_id]
            ])->get();
        $folder = \Str::snake($this::getCompanyName())."/rules_of_procedure";
        $route_name = "rules-of-procedures";
        $doc_type="Poslovnik";
        return view('documents.index', compact('documents', 'folder', 'route_name','doc_type'));
    }

    public function create()
    {
        $this->authorize('create', Document::class);
        return view('documents.create',
            [
                'url' => route('rules-of-procedures.store'),
                'back' => route('rules-of-procedures.index'),
                'doc_type'=>'Poslovnici'
            ]
        );
    }

    public function store(RulesOfProcedureRequest $request)
    {
        $this->authorize('create', Document::class);

        $upload_path = \Str::snake($this::getCompanyName())."/rules_of_procedure";

        try{
            $document = Document::create($request->except('file'));
            Storage::putFileAs($upload_path, $request->file, $request->file_name);
            $request->session()->flash('status', 'Dokument je uspešno sačuvan!');
            CustomLog::info('Dokument Poslovnik "'.$document->document_name.'" kreiran, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja dokumenta Poslovnik, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s').' Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
            $request->session()->flash('warning', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/rules-of-procedures');
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
                'url' => route('rules-of-procedures.update', $document->id),
                'folder' => 'rules_of_procedure',
                'back' => route('rules-of-procedures.index'),
                'doc_type'=>'Poslovnici'
            ]
        );
    }

    public function update(RulesOfProcedureRequest $request, $id)
    {
        $this->authorize('update', Document::find($id));
        $document = Document::findOrFail($id);

        try{
            if($request->file){
                $upload_path = \Str::snake($this::getCompanyName())."/rules_of_procedure";
                Storage::putFileAs($upload_path, $request->file, $request->file_name);
            }
            $document->update($request->except('file'));
            CustomLog::info('Dokument Poslovnik "'.$document->document_name.'" izmenjen, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Dokument je uspešno izmenjen!');
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene dokumenta Poslovnik'.$document->document_name.', '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s').' Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
            $request->session()->flash('warning', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/rules-of-procedures');
    }

    public function destroy($id)
    {
        $this->authorize('delete', Document::find($id));
        $doc_name = Document::find($id)->document_name;
        
        try{
            Document::destroy($id);
            CustomLog::info('Dokument Poslovnik "'.$doc_name.'" uklonjen, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
            return back()->with('status', 'Dokument je uspešno uklonjen');
        } catch(Exception $e) {
            CustomLog::warning('Neuspeli pokušaj brisanja dokumenta Poslovnik'.$doc_name.', '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s').' Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
            return back()->with('warning', 'Došlo je do greške! Pokušajte ponovo.');
        }
    }
}
