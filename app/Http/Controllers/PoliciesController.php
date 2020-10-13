<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PoliciesController extends Controller
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
        $documents = Document::where('doc_category', 'policy')->where('standard_id', $standardId)->get();
        $folder = "policy";
        $route_name = "policies";
        return view('documents.index', compact('documents', 'folder', 'route_name'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('documents.create',['url'=> route('policies.store')]);
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
            'document_version.required' => 'Unesite verziju dokumenta'
        );

        $validatedData = $request->validate([
            'document_name' => 'required|unique:documents|max:255',
            'document_version' => 'required',
            'file' => 'required|max:10000|mimes:pdf'
        ], $messages);

        $document->doc_category = 'policy';
        $document->document_name = $request->document_name;
        $document->version = $request->document_version;

        if($request->file('file')){
            $file = $request->file;

            $name = $file->getClientOriginalName();
            $document->file_name = 'policy_'.time().'.'.$file->getClientOriginalExtension();
            $standard = $this::getStandard();
            $document->standard_id = $standard;
            $document->save();
            $file->storeAs('/public/policy', $document->file_name);

            $request->session()->flash('status', 'Dokument je uspešno sačuvan!');
            return redirect('/policies');
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
        return view('documents.edit', ['document' => $document, 'url' => route('policies.update', $document->id), 'folder' => 'policy']);
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
            'document_version.required' => 'Unesite verziju dokumenta'
        );

        $validatedData = $request->validate([
            'document_name' => 'required|max:255',
            'document_version' => 'required',
            'file' => 'max:10000|mimes:pdf'
        ], $messages);

        $document->doc_category = 'policy';
        $document->document_name = $request->document_name;
        $document->version = $request->document_version;

        if($request->file('file')){
            $file = $request->file;
            $name = $file->getClientOriginalName();
            $document->file_name = 'policy_'.time().'.'.$file->getClientOriginalExtension();
            $standard = $this::getStandard();
            $document->standard_id = $standard;
            $file->storeAs('/public/policy', $document->file_name);
        }

        $document->save();
        $request->session()->flash('status', 'Dokument je uspešno izmenjen!');
        return redirect('/policies');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Document::destroy($id);
        //logic for deleting the file from the server
        return back()->with('status', 'Dokument je uspešno uklonjen');
    }
}
