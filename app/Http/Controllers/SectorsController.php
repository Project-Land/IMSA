<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sector;

class SectorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sectors = Sector::all();
        return view('sectors.index', compact('sectors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sectors.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = array(
            'name.required' => 'Unesite naziv sektora',
            'name.max' => 'Naziv sektora ne sme biti duži od 190 karaktera',
            'name.unique' => 'Već postoji sektor sa takvim nazivom'
        );

        $request->validate([
            'name' => 'required|unique:sectors|max:190'
        ], $messages);

        $sector = Sector::create(['name' => $request->name]);
        
        $request->session()->flash('status', 'Sektor je uspešno kreiran!');
        return redirect('/sectors');
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
        $sector = Sector::findOrFail($id);
        return view('sectors.edit', compact('sector'));
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
        $sector = Sector::findOrFail($id);

        $messages = array(
            'name.required' => 'Unesite naziv sektora',
            'name.max' => 'Naziv sektora ne sme biti duži od 190 karaktera'
        );

        $request->validate([
            'name' => 'required|max:190'
        ], $messages);

        $sector->name = $request->name;
        $sector->save();

        $request->session()->flash('status', 'Sektor je uspešno izmenjen!');
        return redirect('/sectors');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Sector::destroy($id)){
            return back()->with('status', 'Sektor je uspešno uklonjen');
        }else{
            return back()->with('status', 'Došlo je do greške! Pokušajte ponovo.');
        }
    }
}
