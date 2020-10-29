<?php

namespace App\Http\Controllers;

use App\Models\Stakeholder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Facades\CustomLog;
use App\Http\Requests\StoreStakeholdersRequest;
use App\Http\Requests\UpdateStakeholdersRequest;
use Exception;

class StakeholdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        if($this::getStandard() == null){
            return redirect('/');
        }
        $stakeholders = Stakeholder::where([['standard_id', $this::getStandard()],['team_id',Auth::user()->current_team_id]])->get();
        return view('system_processes.stakeholders.index', compact('stakeholders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Stakeholder::class);
        return view('system_processes.stakeholders.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStakeholdersRequest $request)
    {
        $this->authorize('create', Stakeholder::class);
        
        try{
            $stakeholder = Stakeholder::create([
                'name' => $request->name,
                'standard_id' => $this::getStandard(),
                'expectation' => $request->expectation,
                'response' => $request->response,
                'team_id' => Auth::user()->current_team_id,
                'user_id' => Auth::user()->id
            ]);

            CustomLog::info('Zainteresovana strana "'.$request->name.'" kreirana. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
            $request->session()->flash('status', 'Zainteresovana strana je uspešno sačuvana!');
        }catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja zainteresovane strane-'.$request->name.' . Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s').' Greška- '.$e->getMessage(), 'Firma-'.\Auth::user()->current_team_id);
            $request->session()->flash('status', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/stakeholders');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $stakeholder = Stakeholder::findOrFail($id);
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
        $stakeholder = Stakeholder::findOrFail($id);
        $this->authorize('update', $stakeholder);
        return view('system_processes.stakeholders.edit', compact('stakeholder'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStakeholdersRequest $request, $id)
    {
        $stakeholder = Stakeholder::findOrFail($id);
        $this->authorize('update', $stakeholder);

        $stakeholder->standard_id = $this::getStandard();
        $stakeholder->name = $request->name;
        $stakeholder->expectation = $request->expectation;
        $stakeholder->response = $request->response;

        try{
            $stakeholder->save();
            CustomLog::info('Zainteresovana strana "'.$stakeholder->name.'" izmenjena. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
            $request->session()->flash('status', 'Zainteresovana strana je uspešno izmenjena!');
        }catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene zainteresovane strane id-'.$stakeholder->id.' . Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s').' Greška- '.$e->getMessage(), 'Firma-'.\Auth::user()->current_team_id);
            $request->session()->flash('status', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/stakeholders');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete', Stakeholder::find($id));
        $stakeholder = Stakeholder::findOrFail($id);
        if(Stakeholder::destroy($id)){
            CustomLog::info('Zainteresovana strana "'.$stakeholder->name.'" uklonjena. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
            return back()->with('status', 'Zainteresovana strana je uspešno uklonjena');
        }else{
            return back()->with('status', 'Došlo je do greške! Pokušajte ponovo.');
        }
    }
}
