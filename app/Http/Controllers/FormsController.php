<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Document;
use App\Models\Sector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Requests\FormsRequest;
use App\Facades\CustomLog;

class FormsController extends Controller
{

    public function index()
    {
        if(session('standard') == null){
            return redirect('/')->with('status', array('secondary', __('Izaberite standard')));
        }
        $sector = Sector::where('is_global', 1)->get()->first()->id;

        $documents = Document::where([
                ['doc_category', 'form'],
                ['standard_id', session('standard')],
                ['team_id', Auth::user()->current_team_id]
            ])->orWhere([
                ['sector_id', $sector],
                ['doc_category', 'form'],
                ['team_id', Auth::user()->current_team_id]
            ])->with(['sector'])->get();

        return view('documents.index',
            [
                'documents' => $documents,
                'folder' => Str::snake($this::getCompanyName()).'/form',
                'route_name' => 'forms',
                'doc_type' => 'Obrasci'
            ]
        );

    }

    public function showDeleted()
    {
        if(session('standard') == null){
            return redirect('/')->with('status', array('secondary', __('Izaberite standard!')));
        }

        $documents = Document::onlyTrashed()->where([
            ['doc_category', 'form'],
            ['standard_id', session('standard')],
            ['team_id', Auth::user()->current_team_id],
        ])->get();

        return view('documents.deleted',
            [
                'documents' => $documents,
                'folder' => Str::snake($this::getCompanyName()).'/form',
                'route_name' => 'forms',
                'doc_type' => 'Obrasci',
                'back' => route('forms.index')
            ]
        );
    }

    public function create()
    {
        $this->authorize('create', Document::class);
        $sectors = Sector::where('team_id', Auth::user()->current_team_id)->get();
        return view('documents.create',
            [
                'url' => route('forms.store'),
                'back' => route('forms.index'),
                'sectors' => $sectors,
                'doc_type' => 'Obrasci',
                'category' => 'forms'
            ]
        );
    }

    public function store(FormsRequest $request)
    {
        $this->authorize('create', Document::class);
        $document = new Document();
        $upload_path = Str::snake($this::getCompanyName())."/form";

        try{
            $document = Document::create($request->except('file'));
            Storage::putFileAs($upload_path, $request->file, $request->file_name);
            CustomLog::info('Dokument Obrazac "'.$document->document_name.'" kreiran, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', __('Dokument je uspešno sačuvan')));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja dokumenta Obrazac, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška- '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', __('Došlo je do greške, pokušajte ponovo!')));
        }
        return redirect('/forms');
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
                'url' => route('forms.update', $document->id),
                'folder' => 'form',
                'back' => route('forms.index'),
                'doc_type' => 'Obrasci',
                'sectors' => $sectors,
                'category' => 'forms'
            ]
        );
    }

    public function update(FormsRequest $request, $id)
    {
        $this->authorize('update', Document::find($id));
        $document = Document::findOrFail($id);

        try{
            if($request->file){
                $upload_path = Str::snake($this::getCompanyName())."/form";
                Storage::putFileAs($upload_path, $request->file, $request->file_name);
            }
            $document->update($request->except('file'));
            $request->session()->flash('status', array('info', __('Dokument je uspešno izmenjen')));
            CustomLog::info('Dokument Obrazac "'.$document->document_name.'" izmenjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene dokumenta Obrazac "'.$document->document_name.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška- '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', __('Došlo je do greške, pokušajte ponovo!')));
        }
        return redirect('/forms');
    }

    public function destroy($id)
    {
        $this->authorize('delete', Document::find($id));
        $doc_name = Document::find($id)->document_name;

        try{
            Document::destroy($id);
            CustomLog::info('Dokument Obrazac "'.$doc_name.'" uklonjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', __('Dokument je uspešno uklonjen')));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja dokumenta Obrazac "'.$doc_name.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška- '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', __('Došlo je do greške, pokušajte ponovo!')));
        }
    }

    public function forceDestroy($id)
    {
        $document = Document::withTrashed()->findOrFail($id);
        $this->authorize('delete', $document);

        $path = Str::snake($this::getCompanyName())."/forms/".$document->file_name;

        try{
            Storage::delete($path);
            $document->forceDelete();
            CustomLog::info('Dokument Obrazac "'.$document->document_name.'" trajno uklonjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', __('Dokument je trajno uklonjen')));
        } catch(Exception $e) {
            CustomLog::warning('Neuspeli pokušaj trajnog brisanja dokumenta Obrazac "'.$document->document_name.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', __('Došlo je do greške, pokušajte ponovo!')));
        }
    }

    public function restore($id)
    {
        $document = Document::withTrashed()->findOrFail($id);
        $this->authorize('update', $document);

        try{
            $document->restore();
            CustomLog::info('Dokument Obrazac "'.$document->document_name.'" vraćen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', __('Dokument je uspešno vraćen')));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj vraćanja dokumenta Obrazac "'.$document->document_name.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', __('Došlo je do greške, pokušajte ponovo!')));
        }
    }
}
