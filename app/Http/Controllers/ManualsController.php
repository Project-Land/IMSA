<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Document;
use App\Facades\CustomLog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ManualRequest;

class ManualsController extends Controller
{

    public function index()
    {
        $standardId = $this::getStandard();
        if($standardId == null){
            return redirect('/');
        }
        $documents = Document::where([
                ['doc_category', 'manual'],
                ['standard_id', $standardId],
                ['team_id', Auth::user()->current_team_id]
            ])->get();
        $folder = \Str::snake($this::getCompanyName())."/manuals";
        $route_name = "manuals";
        $doc_type="Uputstva";
        return view('documents.index', compact('documents', 'folder', 'route_name','doc_type'));
    }

    public function create()
    {
        $this->authorize('create', Document::class);
        return view('documents.create',
            [
                'url' => route('manuals.store'),
                'back' => route('manuals.index'),
                'doc_type'=>'Uputstva'
            ]
        );
    }

    public function store(ManualRequest $request)
    {
        $this->authorize('create', Document::class);
        $document = new Document();
        $upload_path = "/public/".\Str::snake($this::getCompanyName())."/manuals";

        try{
            $document = Document::create($request->except('file'));
            Storage::putFileAs($upload_path, $request->file, $request->file_name);
            $request->session()->flash('status', 'Dokument je uspešno sačuvan!');
            CustomLog::info('Dokument Uputstvo "'.$document->document_name.'" kreiran. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), \Auth::user()->currentTeam->name);
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja dokumenta Uputstvo. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s').' Greška- '.$e->getMessage(), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/manuals');
    }

    public function show($id)
    {
        $manuak = Manual::findOrFail($id);
        abort(404);
    }

    public function edit($id)
    {
        $this->authorize('update', Document::find($id));
        $document = Document::findOrFail($id);
        return view('documents.edit',
            [
                'document' => $document,
                'url' => route('manuals.update',$document->id),
                'folder' => 'manuals',
                'back' => route('manuals.index'),
                'doc_type'=>'Uputstva'
            ]
        );
    }

    public function update(ManualRequest $request, $id)
    {
        $this->authorize('update', Document::find($id));
        $document = Document::findOrFail($id);

        try{
            if($request->file){
                $upload_path = "/public/".\Str::snake($this::getCompanyName())."/manuals";
                Storage::putFileAs($upload_path, $request->file, $request->file_name);
            }
            $document->update($request->except('file'));
            $request->session()->flash('status', 'Dokument je uspešno izmenjen!');
            CustomLog::info('Dokument Uputstvo "'.$document->document_name.'" izmenjen. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), \Auth::user()->currentTeam->name);
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene dokumenta Uputstvo "'.$document->document_name.'". Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s').' Greška- '.$e->getMessage(), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/manuals');
    }

    public function destroy($id)
    {
        $this->authorize('delete', Document::find($id));
        $doc_name = Document::find($id)->document_name;

        try{
            Document::destroy($id);
            CustomLog::info('Dokument Uputstvo "'.$doc_name.'" uklonjen. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), \Auth::user()->currentTeam->name);
            return back()->with('status', 'Dokument je uspešno uklonjen');
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja dokumenta Uputstvo "'.$doc_name.'". Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s').' Greška- '.$e->getMessage(), \Auth::user()->currentTeam->name);
            return back()->with('status', 'Došlo je do greške! Pokušajte ponovo.');
        }
    }
}
