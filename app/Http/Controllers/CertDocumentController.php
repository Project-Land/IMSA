<?php

namespace App\Http\Controllers;

use Exception;
use App\Facades\CustomLog;
use Illuminate\Support\Str;
use App\Models\CertDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CertDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $documents=CertDocument::where('team_id', Auth::user()->current_team_id)->get();
        return view('certificate_documents.index',['documents'=>$documents]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', CertDocument::class);
        return view('certificate_documents.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', CertDocument::class);
        $document = new CertDocument();
        $upload_path = Str::snake($this::getCompanyName())."/certification_documents";

        try{
            $document = CertDocument::create($request->except('file'));
            Storage::putFileAs($upload_path, $request->file, $request->file_name);
            CustomLog::info('Dokument Sertifikat "'.$document->document_name.'" kreiran, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', __('Dokument je uspešno sačuvan')));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja dokumenta Sertifikat, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška- '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', __('Došlo je do greške, pokušajte ponovo!')));
        }
        return redirect('/certification-documents');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CertDocument  $certDocument
     * @return \Illuminate\Http\Response
     */
    public function show(CertDocument $certDocument)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CertDocument  $certDocument
     * @return \Illuminate\Http\Response
     */
    public function edit(CertDocument $certDocument)
    {
        $this->authorize('update', $certDocument);
        $document = $certDocument;
        return view('certification_documents.edit',
            [
                'document' => $document,
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CertDocument  $certDocument
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CertDocument $certDocument)
    {
        $this->authorize('update', $certDocument);
        $document = $certDocument;

        try{
            if($request->file){
                $upload_path = Str::snake($this::getCompanyName())."/certification-documents";
                Storage::putFileAs($upload_path, $request->file, $request->file_name);
            }
            $document->update($request->except('file'));
            $request->session()->flash('status', array('info', __('Dokument je uspešno izmenjen')));
            CustomLog::info('Dokument Sertifikat "'.$document->document_name.'" izmenjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene dokumenta Sertifikat "'.$document->document_name.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška- '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', __('Došlo je do greške, pokušajte ponovo!')));
        }
        return redirect('/certification-documents');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CertDocument  $certDocument
     * @return \Illuminate\Http\Response
     */
    public function destroy(CertDocument $certDocument)
    {
        $this->authorize('delete',$certDocument );
        $doc_name = $certDocument->document_name;

        try{
            $certDocument->delete();
            CustomLog::info('Dokument Sertifikat "'.$doc_name.'" uklonjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', __('Dokument je uspešno uklonjen')));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja dokumenta Sertifikat "'.$doc_name.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška- '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', __('Došlo je do greške, pokušajte ponovo!')));
        }
    }

    public function showDeleted()
    {

        $documents = CertDocument::onlyTrashed()->where([
            ['team_id', Auth::user()->current_team_id],
        ])->get();

        return view('certificate_documents.deleted',
            [
                'documents' => $documents,
                'folder' => Str::snake($this::getCompanyName()).'/certification_documents',
            ]
        );
    }

    public function forceDestroy($id)
    {
        $document = CertDocument::withTrashed()->findOrFail($id);
        $this->authorize('delete', $document);

        $path = Str::snake($this::getCompanyName())."/certification_documents/".$document->file_name;

        try{
            Storage::delete($path);
            $document->forceDelete();
            CustomLog::info('Dokument Sertifikat "'.$document->document_name.'" trajno uklonjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', __('Dokument je trajno uklonjen')));
        } catch(Exception $e) {
            CustomLog::warning('Neuspeli pokušaj trajnog brisanja dokumenta Sertifikat "'.$document->document_name.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', __('Došlo je do greške, pokušajte ponovo!')));
        }
    }

    public function restore($id)
    {
        $document = CertDocument::withTrashed()->findOrFail($id);
        $this->authorize('update', $document);

        try{
            $document->restore();
            CustomLog::info('Dokument Sertifikat "'.$document->document_name.'" vraćen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', __('Dokument je uspešno vraćen')));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj vraćanja dokumenta Sertifikat "'.$document->document_name.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', __('Došlo je do greške, pokušajte ponovo!')));
        }
    }
}
