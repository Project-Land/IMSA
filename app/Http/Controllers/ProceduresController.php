<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Sector;
use App\Models\Document;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProcedureRequest;
use App\Facades\CustomLog;

class ProceduresController extends Controller
{
    private  $help_video_sr="pFNUWztmJfM";
    private  $help_video_en="sIEwXPU1EGM";
    private  $help_video_hr="pFNUWztmJfM";
    private  $help_video_it="sIEwXPU1EGM";

    public function index($id = null)
    {
        if(empty(session('standard'))){
            return redirect('/')->with('status', array('secondary', __('Izaberite standard!')));
        }

        $sector = Sector::where('is_global', 1)->get()->first()->id;

        $documents = Document::where([
                ['doc_category', 'procedure'],
                ['standard_id', session('standard')],
                ['team_id', Auth::user()->current_team_id]
            ])->when($id, function ($query, $id) {
                return $query->where('sector_id', $id);
            })->orWhere([
                ['sector_id', $sector],
                ['doc_category', 'procedure'],
                ['team_id', Auth::user()->current_team_id]
            ])->when($id, function ($query, $id) {
                return $query->where('sector_id', $id);
            })->get();

        return view('documents.index',
            [
                'documents' => $documents,
                'folder' => Str::snake($this::getCompanyName()).'/procedure',
                'route_name' => 'procedures',
                'doc_type' => 'Procedure',
                'help_video' => $this->{'help_video_'.session('locale')},
                
            ]
        );
    }

    public function showDeleted()
    {
        if(empty(session('standard'))){
            return redirect('/')->with('status', array('secondary', __('Izaberite standard!')));
        }

        $documents = Document::onlyTrashed()->where([
                ['doc_category', 'procedure'],
                ['standard_id', session('standard')],
                ['team_id', Auth::user()->current_team_id],
            ])->get();

        return view('documents.deleted',
            [
                'documents' => $documents,
                'folder' => Str::snake($this::getCompanyName()).'/procedure',
                'route_name' => 'procedures',
                'doc_type' => 'Procedure',
                'back' => route('procedures.index')
            ]
        );
    }

    public function create()
    {
        if(empty(session('standard'))){
            return redirect('/');
        }
        $this->authorize('create', Document::class);
        $sectors = Sector::where('team_id', Auth::user()->current_team_id)->get();
        return view('documents.create',
            [
                'url' => route('procedures.store'),
                'back' => route('procedures.index'),
                'category' => 'procedures',
                'sectors' => $sectors,
                'doc_type'=>'Procedure'
            ]
        );
    }

    public function store(ProcedureRequest $request)
    {
        $this->authorize('create', Document::class);
        $upload_path = Str::snake($this::getCompanyName())."/procedure";
        try{
            $document = Document::create($request->except('file'));
            Storage::putFileAs($upload_path, $request->file, $request->file_name);
            $request->session()->flash('status', array('info', __('Dokument je uspešno sačuvan')));
            CustomLog::info('Dokument Procedure "'.$document->document_name.'" kreiran, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja dokumenta Procedure, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', __('Došlo je do greške, pokušajte ponovo!')));
        }
        return redirect('/procedures');
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
                'url' => route('procedures.update', $document->id),
                'folder' => 'procedure',
                'back' => route('procedures.index'),
                'category' => 'procedures',
                'sectors' => $sectors,
                'doc_type'=>'Procedure'
            ]
        );
    }

    public function update(ProcedureRequest $request, $id)
    {
        $this->authorize('update', Document::find($id));
        $document = Document::findOrFail($id);

        try{
            if($request->file){
                $upload_path = Str::snake($this::getCompanyName())."/procedure";
                Storage::putFileAs($upload_path, $request->file, $request->file_name);
            }
            $document->update($request->except('file'));
            CustomLog::info('Dokument Procedure "'.$document->document_name.'" izmenjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', __('Dokument je uspešno izmenjen')));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene dokumenta Procedure '.$document->document_name.', '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', __('Došlo je do greške, pokušajte ponovo!')));
        }
        return redirect('/procedures');
    }

    public function destroy($id)
    {
        $this->authorize('delete', Document::find($id));
        $doc_name = Document::find($id)->document_name;

        try{
            Document::destroy($id);
            CustomLog::info('Dokument Procedure "'.$doc_name.'" uklonjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', __('Dokument je uspešno uklonjen')));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja dokumenta Procedure '.$doc_name.', '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', __('Došlo je do greške, pokušajte ponovo!')));
        }
    }

    public function forceDestroy($id)
    {
        $document = Document::withTrashed()->findOrFail($id);
        $this->authorize('delete', $document);

        $path = Str::snake($this::getCompanyName())."/procedure/".$document->file_name;

        try{
            Storage::delete($path);
            $document->forceDelete();
            CustomLog::info('Dokument Procedure "'.$document->document_name.'" trajno uklonjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', __('Dokument je trajno uklonjen')));
        } catch(Exception $e) {
            CustomLog::warning('Neuspeli pokušaj trajnog brisanja dokumenta Procedure "'.$document->document_name.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', __('Došlo je do greške, pokušajte ponovo!')));
        }
    }

    public function restore($id)
    {
        $document = Document::withTrashed()->findOrFail($id);
        $this->authorize('update', $document);

        try{
            $document->restore();
            CustomLog::info('Dokument Procedure "'.$document->document_name.'" vraćen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', __('Dokument je uspešno vraćen')));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj vraćanja dokumenta Procedure "'.$document->document_name.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', __('Došlo je do greške, pokušajte ponovo!')));
        }
    }

}
