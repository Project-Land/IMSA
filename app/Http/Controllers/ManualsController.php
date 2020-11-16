<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Document;
use App\Models\Sector;
use App\Facades\CustomLog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ManualRequest;

class ManualsController extends Controller
{

    public function index()
    {
        if(session('standard') == null){
            return redirect('/')->with('status', 'Izaberite standard!');
        }
        
        $sector = Sector::where('is_global', 1)->get()->first()->id;

        $documents = Document::where([
                ['doc_category', 'manual'],
                ['standard_id', session('standard')],
                ['team_id', Auth::user()->current_team_id]
            ])->orWhere([
                ['sector_id', $sector],
                ['doc_category', 'manual'],
                ['team_id', Auth::user()->current_team_id]
            ])->get();

        return view('documents.index', 
            [
                'documents' => $documents,
                'folder' => \Str::snake($this::getCompanyName()).'/manuals',
                'route_name' => 'manuals',
                'doc_type' => 'Uputstva'
            ]
        );
    }

    public function showDeleted()
    {
        if(session('standard') == null){
            return redirect('/')->with('status', 'Izaberite standard!');
        }

        $documents = Document::onlyTrashed()->where([
                ['doc_category', 'manual'],
                ['standard_id', session('standard')],
                ['team_id', Auth::user()->current_team_id],
            ])->get();

        return view('documents.deleted', 
            [
                'documents' => $documents,
                'folder' => \Str::snake($this::getCompanyName()).'/manuals',
                'route_name' => 'manuals',
                'doc_type' => 'Uputstva',
                'back' => route('manuals.index')
            ]
        );
    }

    public function create()
    {
        $this->authorize('create', Document::class);
        $sectors = Sector::where('team_id', Auth::user()->current_team_id)->get();
        return view('documents.create',
            [
                'url' => route('manuals.store'),
                'back' => route('manuals.index'),
                'doc_type'=>'Uputstva',
                'sectors' => $sectors,
                'category' => 'manuals'
            ]
        );
    }

    public function store(ManualRequest $request)
    {
        $this->authorize('create', Document::class);
        $document = new Document();
        $upload_path = \Str::snake($this::getCompanyName())."/manuals";

        try{
            $document = Document::create($request->except('file'));
            Storage::putFileAs($upload_path, $request->file, $request->file_name);
            $request->session()->flash('status', 'Dokument je uspešno sačuvan!');
            CustomLog::info('Dokument Uputstvo "'.$document->document_name.'" kreiran, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja dokumenta Uputstvo, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
            $request->session()->flash('warning', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/manuals');
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
        $sectors = Sector::where('team_id', Auth::user()->current_team_id)->get();
        return view('documents.edit',
            [
                'document' => $document,
                'url' => route('manuals.update',$document->id),
                'folder' => 'manuals',
                'back' => route('manuals.index'),
                'doc_type' => 'Uputstva',
                'sectors' => $sectors,
                'category' => 'manuals'
            ]
        );
    }

    public function update(ManualRequest $request, $id)
    {
        $this->authorize('update', Document::find($id));
        $document = Document::findOrFail($id);

        try{
            if($request->file){
                $upload_path = \Str::snake($this::getCompanyName())."/manuals";
                Storage::putFileAs($upload_path, $request->file, $request->file_name);
            }
            $document->update($request->except('file'));
            $request->session()->flash('status', 'Dokument je uspešno izmenjen!');
            CustomLog::info('Dokument Uputstvo "'.$document->document_name.'" izmenjen, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene dokumenta Uputstvo "'.$document->document_name.'", '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
            $request->session()->flash('warning', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/manuals');
    }

    public function destroy($id)
    {
        $this->authorize('delete', Document::find($id));
        $doc_name = Document::find($id)->document_name;

        try{
            Document::destroy($id);
            CustomLog::info('Dokument Uputstvo "'.$doc_name.'" uklonjen, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
            return back()->with('status', 'Dokument je uspešno uklonjen');
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja dokumenta Uputstvo "'.$doc_name.'", '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
            return back()->with('warning', 'Došlo je do greške! Pokušajte ponovo.');
        }
    }

    public function forceDestroy($id)
    {
        $document = Document::withTrashed()->findOrFail($id);
        $this->authorize('delete', $document);
        $doc_name = $document->document_name;

        $path = \Str::snake($this::getCompanyName())."/manuals/".$document->file_name;
        
        try{
            Storage::delete($path);
            $document->forceDelete();
            CustomLog::info('Dokument Upustvo "'.$doc_name.'" trajno uklonjen, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
            return back()->with('status', 'Dokument je trajno uklonjen');
        } catch(Exception $e) {
            CustomLog::warning('Neuspeli pokušaj trajnog brisanja dokumenta Upustvo "'.$doc_name.'", '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
            return back()->with('warning', 'Došlo je do greške! Pokušajte ponovo.');
        }
    }

    public function restore($id)
    {
        $document = Document::withTrashed()->findOrFail($id);
        $this->authorize('update', $document);

        try{
            $document->restore();
            CustomLog::info('Dokument Uputstvo "'.$document->document_name.'" vraćen, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
            return back()->with('status', 'Dokument je uspešno vraćen');
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj vraćanja dokumenta Uputstvo "'.$document->document_name.'", '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
            return back()->with('warning', 'Došlo je do greške! Pokušajte ponovo.');
        }
    }
}
