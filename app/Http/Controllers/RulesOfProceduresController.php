<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Facades\CustomLog;
use App\Http\Requests\StoreRulesOfProcedureRequest;
use App\Http\Requests\UpdateRulesOfProcedureRequest;
use Exception;

class RulesOfProceduresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $standardId = $this::getStandard();
        if($standardId == null){
            return redirect('/');
        }
        $documents = Document::where([ ['doc_category', 'rules_procedure'], ['standard_id', $standardId], ['team_id',Auth::user()->current_team_id] ])->get();
        $folder = \Str::snake($this::getCompanyName())."/rules_of_procedure";
        $route_name = "rules-of-procedures";
        return view('documents.index', compact('documents', 'folder', 'route_name'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Document::class);
        return view('documents.create', ['url' => route('rules-of-procedures.store'), 'back' => route('rules-of-procedures.index')]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRulesOfProcedureRequest $request)
    {
        $this->authorize('create', Document::class);
        $document = new Document();

        $document->doc_category = 'rules_procedure';
        $document->document_name = $request->document_name;
        $document->version = $request->document_version;
        $document->team_id = Auth::user()->current_team_id;
        $document->user_id = Auth::user()->id;

        $upload_path = "/public/".\Str::snake($this::getCompanyName())."/rules_of_procedure";

        if($request->file('file')){
            try{
                $file = $request->file;
                $name = $file->getClientOriginalName();
                $document->file_name = 'rules_of_procedure_'.time().'.'.$file->getClientOriginalExtension();
                $standard = $this::getStandard();
                $document->standard_id = $standard;
                $document->save();
                $file->storeAs($upload_path, $document->file_name);
                $request->session()->flash('status', 'Dokument je uspešno sačuvan!');
                CustomLog::info('Dokument Poslovnik "'.$document->document_name.'" kreiran. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
            }catch(Exception $e){
                CustomLog::warning('Neuspeli pokušaj kreiranja dokumenta poslovnik. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s').' Greška- '.$e->getMessage(), 'Firma-'.\Auth::user()->current_team_id);
                $request->session()->flash('status', 'Došlo je do greške, pokušajte ponovo!');
            }
            return redirect('/rules-of-procedures');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('update', Document::find($id));
        $document = Document::findOrFail($id);
        return view('documents.edit', ['document' => $document, 'url' => route('rules-of-procedures.update', $document->id), 'folder' => 'rules_of_procedure', 'back' => route('rules-of-procedures.index')]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRulesOfProcedureRequest $request, $id)
    {
        $this->authorize('update', Document::find($id));
        $document = Document::findOrFail($id);

        $document->doc_category = 'rules_procedure';
        $document->document_name = $request->document_name;
        $document->version = $request->document_version;

        $upload_path = "/public/".\Str::snake($this::getCompanyName())."/rules_of_procedure";

        if($request->file('file')){
            $file = $request->file;
            $name = $file->getClientOriginalName();
            $document->file_name = 'rules_of_procedure_'.time().'.'.$file->getClientOriginalExtension();
            $standard = $this::getStandard();
            $document->standard_id = $standard;
            $file->storeAs($upload_path, $document->file_name);
        }
        try{
        $document->save();
        $request->session()->flash('status', 'Dokument je uspešno izmenjen!');
        CustomLog::info('Dokument Poslovnik "'.$document->document_name.'" izmenjen. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
        }catch(Exception $e){
        CustomLog::warning('Neuspeli pokušaj izmene dokumenta poslovnik id-'.$document->id.'. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s').' Greška- '.$e->getMessage(), 'Firma-'.\Auth::user()->current_team_id);
        $request->session()->flash('status', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/rules-of-procedures');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete', Document::find($id));
        $doc_name = Document::find($id)->document_name;
        
        if(Document::destroy($id)){
            CustomLog::info('Dokument Poslovnik "'.$doc_name.'" uklonjen. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
            return back()->with('status', 'Dokument je uspešno uklonjen');
        }else{
            return back()->with('status', 'Došlo je do greške! Pokušajte ponovo.');
        }
    }
}
