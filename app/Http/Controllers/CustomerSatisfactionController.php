<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facades\CustomLog;
use App\Models\CustomerSatisfaction;
use App\Models\SatisfactionColumn;
use Illuminate\Support\Facades\Auth;

class CustomerSatisfactionController extends Controller
{
    public function index()
    {
        if(empty(session('standard'))){
            return redirect('/');
        }

        $cs = CustomerSatisfaction::where([
            ['standard_id', session('standard')],
            ['team_id', Auth::user()->current_team_id]
        ])->get();

        $poll = SatisfactionColumn::where('team_id', Auth::user()->current_team_id)->whereNotNull('name')->get();
        return view('system_processes.customer_satisfaction.index', compact('cs', 'poll'));
    }

    public function create()
    {
        $poll = SatisfactionColumn::where('team_id', Auth::user()->current_team_id)->whereNotNull('name')->get();
        return view('system_processes.customer_satisfaction.create', compact('poll'));
    }

    public function store(Request $request)
    {

        $cs = $request->except('_token');
        $cs['standard_id'] = session('standard');
        $cs['team_id'] = Auth::user()->current_team_id;
        $cs['user_id'] = Auth::user()->id;

        CustomerSatisfaction::create($cs);

        CustomLog::info('Anketa zadovoljstva korisnika popunjena, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
        $request->session()->flash('status', array('info', __('Anketa zadovoljstva korisnika je uspešno popunjena!')));
        return redirect('/customer-satisfaction');
    }

    
    public function show($id)
    {
        $cs = CustomerSatisfaction::findOrFail($id);
        $poll = SatisfactionColumn::where('team_id', Auth::user()->current_team_id)->whereNotNull('name')->get();

        $cs->columns = $poll;
        $cs->average = $cs->average();
        return $cs;
    }

    public function edit($id)
    {
        $poll = SatisfactionColumn::where('team_id', Auth::user()->current_team_id)->whereNotNull('name')->get();
        $cs = CustomerSatisfaction::findOrFail($id);
        return view('system_processes.customer_satisfaction.edit', compact('poll', 'cs'));
    }

    public function update(Request $request, $id)
    {
        $cs = CustomerSatisfaction::findOrFail($id);
        
        try{
            $cs->update($request->except('_token', '_method'));
            CustomLog::info('Anketa za klijenta "'.$cs->customer.'" izmenjena, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', __('Anketa je uspešno izmenjena!')));
        } catch (Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene ankete za korisnika '.$cs->customer.', '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', __('Došlo je do greške, pokušajte ponovo!')));
        }
        return redirect('/customer-satisfaction');
    }

    public function destroy($id)
    {
        $cs = CustomerSatisfaction::findOrFail($id);

        try{
            CustomerSatisfaction::destroy($id);
            CustomLog::info('Anketa za klijenta "'.$cs->customer.'" uklonjena, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', __('Anketa je uspešno uklonjena')));
        } catch (Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja ankete za korisnika '.$cs->customer.', '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', __('Došlo je do greške! Pokušajte ponovo.')));
        }
    }
}
