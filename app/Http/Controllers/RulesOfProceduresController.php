<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

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
        $documents = Document::where('doc_category', 'rules_procedure')->where('standard_id', $standardId)->get();
        return view('documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('documents.create',['url'=> action([RulesOfProceduresController::class, 'store'])]);
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

        $validatedData = $request->validate([
            'doc_name' => 'required|unique:documents|max:255',
            'version' => 'required'
        ]);

        $document->doc_category = 'rules_procedure';
        $document->doc_name = $request->document_name;
        $document->version = $request->version;

        if($request->file('file')){
            $file = $request->file;

            $name = $file->getClientOriginalName();
            $document->file_name = '_rules_of_procedure'.time().'.'.request()->file->getClientOriginalExtension();

            $document->allowed_type = 'pdf';
            $document->can_download = 1;
            $document->visitor_can_download = 1;
            $standard = $this::getStandard();
            $document->standard_id = $standard;
            $document->save();
            $file->storeAs('rules_of_procedure', $document->file_name);

            $request->session()->flash('status', 'Dokument je uspešno sačuvan!');
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
