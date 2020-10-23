<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProceduresController extends Controller
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
        $documents = Document::where('doc_category', 'procedure')->where([['standard_id', $standardId],['team_id',Auth::user()->current_team_id]])->get();
        $folder = "procedure";
        $route_name = "procedures";
        return view('documents.index', compact('documents', 'folder', 'route_name'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sectors = Sector::all();
        return view('documents.create',['url'=> route('procedures.store'), 'back' => route('procedures.index'), 'category' => 'procedures', 'sectors' => $sectors]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $document = new Document();

        $messages = array(
            'file.required' => 'Izaberite fajl',
            'file.mimes' => 'Fajl mora biti u PDF formatu',
            'document_name.required' => 'Unesite naziv dokumenta',
            'document_name.max' => 'Naziv dokumenta ne sme biti duži od 255 karaktera',
            'document_name.unique' => 'Već postoji dokument sa takvim nazivom',
            'document_version.required' => 'Unesite verziju dokumenta',
            'sector.required' => 'Izaberite pripadajući sektor'
        );

        $validatedData = $request->validate([
            'document_name' => 'required|unique:documents|max:255',
            'document_version' => 'required',
            'file' => 'required|max:10000|mimes:pdf',
            'sector' => 'required'
        ], $messages);

        $document->doc_category = 'procedure';
        $document->document_name = $request->document_name;
        $document->version = $request->document_version;
        $document->sector_id = $request->sector;

        if($request->file('file')){
            $file = $request->file;

            $name = $file->getClientOriginalName();
            $document->file_name = 'procedure_'.time().'.'.$file->getClientOriginalExtension();
            $standard = $this::getStandard();
            $document->standard_id = $standard;
            $document->save();
            $file->storeAs('/public/procedure', $document->file_name);

            $request->session()->flash('status', 'Dokument je uspešno sačuvan!');
            return redirect('/procedures');
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
        $document = Document::findOrFail($id);
        $sectors = Sector::all();
        return view('documents.edit',['document'=>$document,'url'=>route('procedures.update',$document->id), 'folder' => 'procedure', 'back' => route('procedures.index'), 'category' => 'procedures', 'sectors' => $sectors]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $document = Document::findOrFail($id);

        $messages = array(
            'file.mimes' => 'Fajl mora biti u PDF formatu',
            'document_name.required' => 'Unesite naziv dokumenta',
            'document_name.max' => 'Naziv dokumenta ne sme biti duži od 255 karaktera',
            'document_version.required' => 'Unesite verziju dokumenta',
            'sector.required' => 'Izaberite pripadajući sektor'
        );

        $validatedData = $request->validate([
            'document_name' => 'required|max:255',
            'document_version' => 'required',
            'file' => 'max:10000|mimes:pdf',
            'sector' => 'required'
        ], $messages);

        $document->doc_category = 'procedure';
        $document->document_name = $request->document_name;
        $document->version = $request->document_version;
        $document->sector_id = $request->sector;

        if($request->file('file')){
            $file = $request->file;
            $name = $file->getClientOriginalName();
            $document->file_name = 'procedure_'.time().'.'.$file->getClientOriginalExtension();
            $standard = $this::getStandard();
            $document->standard_id = $standard;
            $file->storeAs('/public/procedure', $document->file_name);
        }

        $document->save();
        $request->session()->flash('status', 'Dokument je uspešno izmenjen!');
        return redirect('/procedures');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Document::destroy($id)){
            //logic for deleting the file from the server
            return back()->with('status', 'Dokument je uspešno uklonjen');
        }else{
            return back()->with('status', 'Došlo je do greške! Pokušajte ponovo.');
        }
    }
}
