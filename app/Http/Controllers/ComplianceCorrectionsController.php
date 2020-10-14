<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ComplianceCorrection;

class ComplianceCorrectionsController extends Controller
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
        $documents = ComplianceCorrection::where('standard_id', $standardId)->get();
        return view('system_processes.compliance_correction.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('system_processes.compliance_correction.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $document = new ComplianceCorrection();

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

        $document->document_name = $request->document_name;
        $document->version = $request->document_version;

        if($request->file('file')){
            $file = $request->file;

            $name = $file->getClientOriginalName();
            $document->file_name = 'compliance_correction_'.time().'.'.$file->getClientOriginalExtension();
            $standard = $this::getStandard();
            $document->standard_id = $standard;
            $document->save();
            $file->storeAs('/public/compliance_correction', $document->file_name);

            $request->session()->flash('status', 'Dokument je uspešno sačuvan!');
            return redirect('/compliance-correction');
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
        $document = ComplianceCorrection::findOrFail($id);
        return view('system_processes.compliance_correction.edit', compact('document'));
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
        $document = ComplianceCorrection::findOrFail($id);

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

        $document->document_name = $request->document_name;
        $document->version = $request->document_version;

        if($request->file('file')){
            $file = $request->file;
            $name = $file->getClientOriginalName();
            $document->file_name = 'compliance_correction_'.time().'.'.$file->getClientOriginalExtension();
            $standard = $this::getStandard();
            $document->standard_id = $standard;
            $file->storeAs('/public/compliance_correction', $document->file_name);
        }

        $document->save();
        $request->session()->flash('status', 'Dokument je uspešno izmenjen!');
        return redirect('/compliance-correction');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ComplianceCorrection::destroy($id);
        //logic for deleting the file from the server
        return back()->with('status', 'Dokument je uspešno uklonjen');
    }
}
