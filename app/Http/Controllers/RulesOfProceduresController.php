<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Document;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Facades\CustomLog;
use App\Http\Requests\RulesOfProcedureRequest;

class RulesOfProceduresController extends Controller
{
    private  $help_video_sr="hxq6f8T4CTw";
    private  $help_video_en="hxq6f8T4CTw";
    private  $help_video_hr="hxq6f8T4CTw";
    private  $help_video_it="hxq6f8T4CTw";

    public function index()
    {
        if(empty(session('standard'))){
            return redirect('/')->with('status', array('secondary', 'Izaberite standard!'));
        }

        $documents = Document::where([
                ['doc_category', 'rules_procedure'],
                ['standard_id', session('standard')],
                ['team_id', Auth::user()->current_team_id]
            ])->get();

        return view('documents.index',
            [
                'documents' => $documents,
                'folder' => Str::snake($this::getCompanyName())."/rules_of_procedure",
                'route_name' => 'rules-of-procedures',
                'doc_type' => 'Poslovnik',
                'help_video' => $this->{'help_video_'.session('locale')}
            ]
        );
    }

    public function showDeleted()
    {
        if(empty(session('standard'))){
            return redirect('/')->with('status', array('secondary', 'Izaberite standard!'));
        }

        $documents = Document::onlyTrashed()->where([
                ['doc_category', 'rules_procedure'],
                ['standard_id', session('standard')],
                ['team_id', Auth::user()->current_team_id],
            ])->get();

        return view('documents.deleted',
            [
                'documents' => $documents,
                'folder' => Str::snake($this::getCompanyName())."/rules_of_procedure",
                'route_name' => 'rules-of-procedures',
                'doc_type' => 'Poslovnik',
                'back' => route('rules-of-procedures.index'),
                'help_video' => $this->{'help_video_'.session('locale')}
            ]
        );
    }

    public function create()
    {
        if(empty(session('standard'))){
            return redirect('/');
        }
        $this->authorize('create', Document::class);
        return view('documents.create',
            [
                'url' => route('rules-of-procedures.store'),
                'back' => route('rules-of-procedures.index'),
                'doc_type'=>'Poslovnici',
                'help_video' => $this->{'help_video_'.session('locale')}
            ]
        );
    }

    public function store(RulesOfProcedureRequest $request)
    {
        $this->authorize('create', Document::class);

        $upload_path = Str::snake($this::getCompanyName())."/rules_of_procedure";

        try{
            $document = Document::create($request->except('file'));
            Storage::putFileAs($upload_path, $request->file, $request->file_name);
            $request->session()->flash('status', array('info', 'Dokument je uspešno sačuvan'));
            CustomLog::info('Dokument Poslovnik "'.$document->document_name.'" kreiran, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja dokumenta Poslovnik, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
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
                'doc_type'=>'Poslovnici',
                'help_video' => $this->{'help_video_'.session('locale')}
            ]
        );
    }

    public function update(RulesOfProcedureRequest $request, $id)
    {
        $this->authorize('update', Document::find($id));
        $document = Document::findOrFail($id);

        try{
            if($request->file){
                $upload_path = Str::snake($this::getCompanyName())."/rules_of_procedure";
                Storage::putFileAs($upload_path, $request->file, $request->file_name);
            }
            $document->update($request->except('file'));
            CustomLog::info('Dokument Poslovnik "'.$document->document_name.'" izmenjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', 'Dokument je uspešno izmenjen'));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene dokumenta Poslovnik'.$document->document_name.', '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }
        return redirect('/rules-of-procedures');
    }

    public function destroy($id)
    {
        $this->authorize('delete', Document::find($id));
        $doc_name = Document::find($id)->document_name;

        try{
            Document::destroy($id);
            CustomLog::info('Dokument Poslovnik "'.$doc_name.'" uklonjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', 'Dokument je uspešno uklonjen'));
        } catch(Exception $e) {
            CustomLog::warning('Neuspeli pokušaj brisanja dokumenta Poslovnik'.$doc_name.', '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }
    }

    public function forceDestroy($id)
    {
        $document = Document::withTrashed()->findOrFail($id);
        $this->authorize('delete', $document);

        $path = Str::snake($this::getCompanyName())."/rules_of_procedure/".$document->file_name;

        try{
            Storage::delete($path);
            $document->forceDelete();
            CustomLog::info('Dokument Poslovnik "'.$document->document_name.'" trajno uklonjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', 'Dokument je trajno uklonjen'));
        } catch(Exception $e) {
            CustomLog::warning('Neuspeli pokušaj trajnog brisanja dokumenta Poslovnik "'.$document->document_name.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }
    }

    public function restore($id)
    {
        $document = Document::withTrashed()->findOrFail($id);
        $this->authorize('update', $document);

        try{
            $document->restore();
            CustomLog::info('Dokument Poslovnik "'.$document->document_name.'" vraćen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', 'Dokument je uspešno vraćen'));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj vraćanja dokumenta Poslovnik "'.$document->document_name.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }
    }
}
