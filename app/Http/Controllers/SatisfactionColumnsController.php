<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facades\CustomLog;
use App\Models\SatisfactionColumn;
use Illuminate\Support\Facades\Auth;

class SatisfactionColumnsController extends Controller
{

    public function create()
    {
        return view('system_processes.customer_satisfaction.create_poll');
    }

    public function store(Request $request)
    {
        foreach($request->except('_token') as $key => $req){
            $cols = new SatisfactionColumn;
            $cols->column_name = $key;
            $cols->name = $req;
            $cols->team_id = Auth::user()->current_team_id;
            $cols->save();
        }

        CustomLog::info('Anketa zadovoljstva korisnika kreirana, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
        $request->session()->flash('status', array('info', __('Anketa zadovoljstva korisnika je uspešno sačuvana!')));
        return redirect('/customer-satisfaction');
    }

    public function show($id)
    {
        //
    }

    public function edit($teamId)
    {
        $poll = SatisfactionColumn::where('team_id', $teamId)->get();
        return view('system_processes.customer_satisfaction.edit_poll', compact('poll'));
    }

    public function update(Request $request, $id)
    {
        $cols = SatisfactionColumn::where('team_id', Auth::user()->current_team_id)->get();
        foreach($request->except('_token', '_method') as $key => $req){
            foreach($cols as $col){
                if($col->column_name == $key){
                    $col->name = $req;
                    $col->save();
                }
            }
        }

        CustomLog::info('Anketa zadovoljstva korisnika izmenjena, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
        $request->session()->flash('status', array('info', __('Anketa zadovoljstva korisnika je uspešno izmenjena!')));
        return redirect('/customer-satisfaction');
    }

    public function destroy($id)
    {
        //
    }
}
