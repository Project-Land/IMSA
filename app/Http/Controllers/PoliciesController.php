<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Document;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\PolicyRequest;
use App\Facades\CustomLog;

class PoliciesController extends Controller
{
    private  $help_video_sr="owMDsxR96Zs";
    private  $help_video_en="owMDsxR96Zs";
    private  $help_video_hr="owMDsxR96Zs";
    private  $help_video_it="owMDsxR96Zs";

    public function index()
    {
        if(empty(session('standard'))){
            return redirect('/')->with('status', array('secondary', 'Izaberite standard!'));
        }

        $documents = Document::where([
                ['doc_category', 'policy'],
                ['standard_id', session('standard')],
                ['team_id', Auth::user()->current_team_id]
            ])->get();

        return view('documents.index',
            [
                'documents' => $documents,
                'folder' => Str::snake($this::getCompanyName())."/policy",
                'route_name' => 'policies',
                'doc_type' => 'Politike',
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
                ['doc_category', 'policy'],
                ['standard_id', session('standard')],
                ['team_id', Auth::user()->current_team_id],
            ])->get();

        return view('documents.deleted',
            [
                'documents' => $documents,
                'folder' => Str::snake($this::getCompanyName())."/policy",
                'route_name' => 'policies',
                'doc_type' => 'Politike',
                'back' => route('policies.index'),
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
                'url' => route('policies.store'),
                'back' => route('policies.index'),
                'doc_type'=>'Politike',
                'help_video' => $this->{'help_video_'.session('locale')}
            ]
        );
    }

    public function store(PolicyRequest $request)
    {
        $this->authorize('create', Document::class);
        $document = new Document();
        $upload_path = Str::snake($this::getCompanyName())."/policy";

        try{
            $document = Document::create($request->except('file'));
            Storage::putFileAs($upload_path, $request->file, $request->file_name);
            CustomLog::info('Dokument Politike "'.$document->document_name.'" kreiran, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', 'Dokument je uspešno sačuvan'));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja dokumenta Politike "'.$document->document_name.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
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
                'doc_type'=>'Politike',
                'help_video' => $this->{'help_video_'.session('locale')}
            ]
        );
    }

    public function update(PolicyRequest $request, $id)
    {
        $this->authorize('update', Document::find($id));
        $document = Document::findOrFail($id);

        try{
            if($request->file){
                $upload_path = Str::snake($this::getCompanyName())."/policy";
                Storage::putFileAs($upload_path, $request->file, $request->file_name);
            }
            $document->update($request->except('file'));
            $request->session()->flash('status', array('info', 'Dokument je uspešno izmenjen'));
            CustomLog::info('Dokument Politike "'.$document->document_name.'" izmenjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene dokumenta Politike '.$document->document_name.', '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }
        return redirect('/policies');
    }

    public function destroy($id)
    {
        $this->authorize('delete', Document::find($id));
        $document_name = Document::find($id)->document_name;

        try{
            Document::destroy($id);
            CustomLog::info('Dokument Politike "'.$document_name.'" uklonjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', 'Dokument je uspešno uklonjen'));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja dokumenta Politike '.$document_name.', '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }
    }

    public function forceDestroy($id)
    {
        $document = Document::withTrashed()->findOrFail($id);
        $this->authorize('delete', $document);

        $path = Str::snake($this::getCompanyName())."/policy/".$document->file_name;

        try{
            Storage::delete($path);
            $document->forceDelete();
            CustomLog::info('Dokument Politike "'.$document->document_name.'" trajno uklonjen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', 'Dokument je trajno uklonjen'));
        } catch(Exception $e) {
            CustomLog::warning('Neuspeli pokušaj trajnog brisanja dokumenta Politike "'.$document->document_name.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }
    }

    public function restore($id)
    {
        $document = Document::withTrashed()->findOrFail($id);
        $this->authorize('update', $document);

        try{
            $document->restore();
            CustomLog::info('Dokument Politike "'.$document->document_name.'" vraćen, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', 'Dokument je uspešno vraćen'));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj vraćanja dokumenta Politike "'.$document->document_name.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', 'Došlo je do greške, pokušajte ponovo!'));
        }
    }
}
