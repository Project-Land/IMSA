<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Document;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ExternalDocumentRequest;
use App\Facades\CustomLog;

class ExternalDocumentsController extends Controller
{
    private  $help_video_sr="xEuNHYg07cc";
    private  $help_video_en="xEuNHYg07cc";
    private  $help_video_hr="xEuNHYg07cc";
    private  $help_video_it="xEuNHYg07cc";

    public function index()
    {
        if(session('standard') == null){
            return redirect('/')->with('status', array('secondary', 'Izaberite standard!'));
        }

        $documents = Document::where([
                ['doc_category', 'external_document'],
                ['standard_id', session('standard')],
                ['team_id', Auth::user()->current_team_id]
            ])->get();

        return view('documents.index',
            [
                'documents' => $documents,
                'folder' => Str::snake($this::getCompanyName())."/external_document",
                'route_name' => 'external-documents',
                'doc_type' => 'Eksterna dokumenta',
                'help_video' => $this->{'help_video_'.session('locale')}
            ]
        );
    }

    public function showDeleted()
    {
        if(session('standard') == null){
            return redirect('/')->with('status', array('secondary', 'Izaberite standard!'));
        }

        $documents = Document::onlyTrashed()->where([
                ['doc_category', 'external_document'],
                ['standard_id', session('standard')],
                ['team_id', Auth::user()->current_team_id],
            ])->get();

        return view('documents.deleted',
            [
                'documents' => $documents,
                'folder' => Str::snake($this::getCompanyName())."/external_document",
                'route_name' => 'external-documents',
                'doc_type' => 'Eksterna dokumenta',
                'back' => route('external-documents.index'),
                'help_video' => $this->{'help_video_'.session('locale')}
            ]
        );
    }

    public function create()
    {
        $this->authorize('create', Document::class);
        return view('documents.create',
            [
                'url' => route('external-documents.store'),
                'back' => route('external-documents.index'),
                'doc_type' => 'Eksterna dokumenta',
                'help_video' => $this->{'help_video_'.session('locale')}
            ]
        );
    }

    public function store(ExternalDocumentRequest $request)
    {
        $this->authorize('create', Document::class);
        $document = new Document();
        $upload_path = Str::snake($this::getCompanyName())."/external_document";

        try{
            $document = Document::create($request->except('file'));
            Storage::putFileAs($upload_path, $request->file, $request->file_name);
            CustomLog::info('Eksterni dokument "'.$document->document_name.'" kreiran, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', 'Dokument je uspešno sačuvan'));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja eksternog dokumenta "'.$document->document_name.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }

        return redirect('/external-documents');
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
                'url' => route('external-documents.update', $document->id),
                'folder' => 'external_document',
                'back' => route('external-documents.index'),
                'doc_type' => 'Eksterna dokumenta',
                'help_video' => $this->{'help_video_'.session('locale')}
            ]
        );
    }

    public function update(ExternalDocumentRequest $request, $id)
    {
        $this->authorize('update', Document::find($id));
        $document = Document::findOrFail($id);
        $path = Str::snake($this::getCompanyName())."/external_document/".$document->file_name;

        try{
            if($request->file){
                $upload_path = Str::snake($this::getCompanyName())."/external_document";
                Storage::putFileAs($upload_path, $request->file, $request->file_name);
                Storage::delete($path);
            }
            $document->update($request->except('file'));
            $request->session()->flash('status', array('info', 'Dokument je uspešno izmenjen'));
            CustomLog::info('Eksterni dokument "'.$document->document_name.'" izmenjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene eksternog dokumenta '.$document->document_name.', '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }
        return redirect('/external-documents');
    }

    public function destroy($id)
    {
        $this->authorize('delete', Document::find($id));
        $document_name = Document::find($id)->document_name;

        try{
            Document::destroy($id);
            CustomLog::info('Eksterni dokument "'.$document_name.'" uklonjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', 'Dokument je uspešno uklonjen'));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja eksternog dokumenta '.$document_name.', '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }
    }

    public function forceDestroy($id)
    {
        $document = Document::withTrashed()->findOrFail($id);
        $this->authorize('delete', $document);

        $path = Str::snake($this::getCompanyName())."/external_document/".$document->file_name;

        try{
            Storage::delete($path);
            $document->forceDelete();
            CustomLog::info('Eksterni dokument "'.$document->document_name.'" trajno uklonjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', 'Dokument je trajno uklonjen'));
        } catch(Exception $e) {
            CustomLog::warning('Neuspeli pokušaj trajnog brisanja eksternog dokumenta "'.$document->document_name.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }
    }

    public function restore($id)
    {
        $document = Document::withTrashed()->findOrFail($id);
        $this->authorize('update', $document);

        try{
            $document->restore();
            CustomLog::info('Eksterni dokument "'.$document->document_name.'" vraćen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', 'Dokument je uspešno vraćen'));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj vraćanja eksternog dokumenta "'.$document->document_name.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }
    }
}
