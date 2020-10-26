<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sector;
use App\Models\Team;
use App\Facades\CustomLog;

class SectorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sectors = Sector::where('team_id', \Auth::user()->current_team_id)->get();
        return view('sectors.index', compact('sectors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Sector::class);
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
        $this->authorize('create', Sector::class);
        $messages = array(
            'name.required' => 'Unesite naziv sektora',
            'name.max' => 'Naziv sektora ne sme biti duži od 190 karaktera'
        );

        $request->validate([
            'name' => 'required|max:190'
        ], $messages);

        $sector = Sector::create([
            'name' => $request->name,
            'team_id' => \Auth::user()->current_team_id,
            'user_id' => \Auth::user()->id
        ]);
        CustomLog::info('Sektor "'.$sector->name.'" dodat. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
        
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
        $sector = Sector::findOrFail($id);
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('update', Sector::find($id));
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
        $this->authorize('update', Sector::find($id));
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

        CustomLog::info('Sektor "'.$sector->name.'" izmenjen. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);

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
        $this->authorize('delete', Sector::find($id));
        $name = Sector::find($id)->name;
        try{
            Sector::destroy($id);
            CustomLog::info('Sektor "'.$name.'" obrisan. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
            return back()->with('status', 'Sektor je uspešno uklonjen');
        } catch(\Illuminate\Database\QueryException $e){
            CustomLog::warning('Neuspeli pokušaj brisanja sektora. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
            return back()->with('status', 'Sektor ne može biti uklonjen jer je u direktnoj vezi sa pojedinim sistemskim procesima.');
        }
    }
}
