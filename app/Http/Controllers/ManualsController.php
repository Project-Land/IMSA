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
    private  $help_video_sr = "egpLkXv-bFE";
    private  $help_video_en = "egpLkXv-bFE";
    private  $help_video_hr = "egpLkXv-bFE";
    private  $help_video_it = "egpLkXv-bFE";

    public function index()
    {
        if (empty(session('standard'))) {
            return redirect('/')->with('status', array('secondary', __('Izaberite standard!')));
        }

        $sector = Sector::where('is_global', 1)->where('team_id', Auth::user()->current_team_id)->get()->first()->id;

        $documents = Document::where([
            ['doc_category', 'manual'],
            ['standard_id', session('standard')],
            ['team_id', Auth::user()->current_team_id]
        ])->orWhere([
            ['sector_id', $sector],
            ['doc_category', 'manual'],
            ['team_id', Auth::user()->current_team_id]
        ])->get();

        return view(
            'documents.index',
            [
                'documents' => $documents,
                'folder' => Str::snake($this::getCompanyName()) . '/manuals',
                'route_name' => 'manuals',
                'doc_type' => 'Uputstva',
                'help_video' => $this->{'help_video_' . session('locale')}
            ]
        );
    }

    public function showDeleted()
    {
        if (empty(session('standard'))) {
            return redirect('/')->with('status', array('secondary', __('Izaberite standard!')));
        }

        $documents = Document::onlyTrashed()->where([
            ['doc_category', 'manual'],
            ['standard_id', session('standard')],
            ['team_id', Auth::user()->current_team_id],
        ])->get();

        return view(
            'documents.deleted',
            [
                'documents' => $documents,
                'folder' => Str::snake($this::getCompanyName()) . '/manuals',
                'route_name' => 'manuals',
                'doc_type' => 'Uputstva',
                'back' => route('manuals.index'),
                'help_video' => $this->{'help_video_' . session('locale')}
            ]
        );
    }

    public function create()
    {
        if (empty(session('standard'))) {
            return redirect('/');
        }

        $this->authorize('create', Document::class);
        $sectors = Sector::where('team_id', Auth::user()->current_team_id)->get();
        return view(
            'documents.create',
            [
                'url' => route('manuals.store'),
                'back' => route('manuals.index'),
                'doc_type' => 'Uputstva',
                'sectors' => $sectors,
                'category' => 'manuals',
                'help_video' => $this->{'help_video_' . session('locale')}
            ]
        );
    }

    public function store(ManualRequest $request)
    {
        $this->authorize('create', Document::class);
        $document = new Document();
        $upload_path = Str::snake($this::getCompanyName()) . "/manuals";

        try {
            $document = Document::create($request->except('file'));
            Storage::putFileAs($upload_path, $request->file, $request->file_name);
            $request->session()->flash('status', array('info', __('Dokument je uspešno sačuvan')));
            CustomLog::info('Dokument Uputstvo "' . $document->document_name . '" kreiran, ' . Auth::user()->name . ', ' . Auth::user()->username . ', ' . date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
        } catch (Exception $e) {
            CustomLog::warning('Neuspeli pokušaj kreiranja dokumenta Uputstvo, ' . Auth::user()->name . ', ' . Auth::user()->username . ', ' . date('d.m.Y H:i:s') . ', Greška: ' . $e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', __('Došlo je do greške, pokušajte ponovo!')));
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
        return view(
            'documents.edit',
            [
                'document' => $document,
                'url' => route('manuals.update', $document->id),
                'folder' => 'manuals',
                'back' => route('manuals.index'),
                'doc_type' => 'Uputstva',
                'sectors' => $sectors,
                'category' => 'manuals',
                'help_video' => $this->{'help_video_' . session('locale')}
            ]
        );
    }

    public function update(ManualRequest $request, $id)
    {
        $this->authorize('update', Document::find($id));
        $document = Document::findOrFail($id);

        try {
            if ($request->file) {
                $upload_path = Str::snake($this::getCompanyName()) . "/manuals";
                Storage::putFileAs($upload_path, $request->file, $request->file_name);
            }
            $document->update($request->except('file'));
            $request->session()->flash('status', array('info', __('Dokument je uspešno izmenjen')));
            CustomLog::info('Dokument Uputstvo "' . $document->document_name . '" izmenjen, ' . Auth::user()->name . ', ' . Auth::user()->username . ', ' . date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
        } catch (Exception $e) {
            CustomLog::warning('Neuspeli pokušaj izmene dokumenta Uputstvo "' . $document->document_name . '", ' . Auth::user()->name . ', ' . Auth::user()->username . ', ' . date('d.m.Y H:i:s') . ', Greška: ' . $e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', __('Došlo je do greške, pokušajte ponovo!')));
        }
        return redirect('/manuals');
    }

    public function destroy($id)
    {
        $this->authorize('delete', Document::find($id));
        $doc_name = Document::find($id)->document_name;

        try {
            Document::destroy($id);
            CustomLog::info('Dokument Uputstvo "' . $doc_name . '" uklonjen, ' . Auth::user()->name . ', ' . Auth::user()->username . ', ' . date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', __('Dokument je uspešno uklonjen')));
        } catch (Exception $e) {
            CustomLog::warning('Neuspeli pokušaj brisanja dokumenta Uputstvo "' . $doc_name . '", ' . Auth::user()->name . ', ' . Auth::user()->username . ', ' . date('d.m.Y H:i:s') . ', Greška: ' . $e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', __('Došlo je do greške, pokušajte ponovo!')));
        }
    }

    public function forceDestroy($id)
    {
        $document = Document::withTrashed()->findOrFail($id);
        $this->authorize('delete', $document);

        $path = Str::snake($this::getCompanyName()) . "/manuals/" . $document->file_name;

        try {
            Storage::delete($path);
            $document->forceDelete();
            CustomLog::info('Dokument Upustvo "' . $document->document_name . '" trajno uklonjen, ' . Auth::user()->name . ', ' . Auth::user()->username . ', ' . date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', __('Dokument je trajno uklonjen')));
        } catch (Exception $e) {
            CustomLog::warning('Neuspeli pokušaj trajnog brisanja dokumenta Upustvo "' . $document->document_name . '", ' . Auth::user()->name . ', ' . Auth::user()->username . ', ' . date('d.m.Y H:i:s') . ', Greška: ' . $e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', __('Došlo je do greške, pokušajte ponovo!')));
        }
    }

    public function restore($id)
    {
        $document = Document::withTrashed()->findOrFail($id);
        $this->authorize('update', $document);

        try {
            $document->restore();
            CustomLog::info('Dokument Uputstvo "' . $document->document_name . '" vraćen, ' . Auth::user()->name . ', ' . Auth::user()->username . ', ' . date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', __('Dokument je uspešno vraćen')));
        } catch (Exception $e) {
            CustomLog::warning('Neuspeli pokušaj vraćanja dokumenta Uputstvo "' . $document->document_name . '", ' . Auth::user()->name . ', ' . Auth::user()->username . ', ' . date('d.m.Y H:i:s') . ', Greška: ' . $e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', __('Došlo je do greške, pokušajte ponovo!')));
        }
    }
}
