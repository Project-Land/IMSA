<?php

namespace App\Http\Controllers;

use Exception;
use App\Facades\CustomLog;
use Illuminate\Support\Str;
use App\Models\CustomerSatisfaction;
use Illuminate\Http\Request;
use App\Models\SatisfactionColumn;
use Illuminate\Support\Facades\Auth;
use App\Exports\CustomerSatisfactionExport;
use Maatwebsite\Excel\Facades\Excel;

class CustomerSatisfactionController extends Controller
{
    public function index()
    {
        if (session('standard') == null || session('standard_name') != "9001") {
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
        if (session('standard') == null || session('standard_name') != "9001") {
            return redirect('/');
        }

        $this->authorize('create', CustomerSatisfaction::class);

        $poll = SatisfactionColumn::where('team_id', Auth::user()->current_team_id)->whereNotNull('name')->get();
        return view('system_processes.customer_satisfaction.create', compact('poll'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', CustomerSatisfaction::class);

        $cs = $request->except('_token', 'date');
        $cs['standard_id'] = session('standard');
        $cs['team_id'] = Auth::user()->current_team_id;
        $cs['user_id'] = Auth::user()->id;
        $cs['date'] = date('Y-m-d H:i', strtotime($request->date));

        CustomerSatisfaction::create($cs);

        CustomLog::info('Anketa zadovoljstva korisnika popunjena, ' . Auth::user()->name . ', ' . Auth::user()->username . ', ' . date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
        $request->session()->flash('status', array('info', __('Anketa zadovoljstva korisnika je uspešno popunjena!')));
        return redirect('/customer-satisfaction');
    }


    public function show($id)
    {
        $cs = CustomerSatisfaction::with('user')->findOrFail($id);
        $poll = SatisfactionColumn::where('team_id', Auth::user()->current_team_id)->whereNotNull('name')->get();

        $cs->columns = $poll;
        $cs->average = round($cs->average(), 1);
        return $cs;
    }

    public function edit($id)
    {
        if (session('standard') == null || session('standard_name') != "9001") {
            return redirect('/');
        }

        $cs = CustomerSatisfaction::findOrFail($id);
        $poll = SatisfactionColumn::where('team_id', Auth::user()->current_team_id)->whereNotNull('name')->get();

        $this->authorize('update', $cs);

        return view('system_processes.customer_satisfaction.edit', compact('poll', 'cs'));
    }

    public function update(Request $request, $id)
    {
        $cs = CustomerSatisfaction::findOrFail($id);

        $this->authorize('update', $cs);

        try {
            $cs['date'] = date('Y-m-d H:i', strtotime($request->date));
            $cs->update($request->except('_token', '_method', 'date'));
            CustomLog::info('Anketa za klijenta "' . $cs->customer . '" izmenjena, ' . Auth::user()->name . ', ' . Auth::user()->username . ', ' . date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', __('Anketa je uspešno izmenjena!')));
        } catch (Exception $e) {
            CustomLog::warning('Neuspeli pokušaj izmene ankete za korisnika ' . $cs->customer . ', ' . Auth::user()->name . ', ' . Auth::user()->username . ', ' . date('d.m.Y H:i:s') . ', Greška: ' . $e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', __('Došlo je do greške, pokušajte ponovo!')));
        }
        return redirect('/customer-satisfaction');
    }

    public function destroy($id)
    {
        $cs = CustomerSatisfaction::findOrFail($id);

        $this->authorize('delete', $cs);

        try {
            CustomerSatisfaction::destroy($id);
            CustomLog::info('Anketa za klijenta "' . $cs->customer . '" uklonjena, ' . Auth::user()->name . ', ' . Auth::user()->username . ', ' . date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', __('Anketa je uspešno uklonjena')));
        } catch (Exception $e) {
            CustomLog::warning('Neuspeli pokušaj brisanja ankete za korisnika ' . $cs->customer . ', ' . Auth::user()->name . ', ' . Auth::user()->username . ', ' . date('d.m.Y H:i:s') . ', Greška: ' . $e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', __('Došlo je do greške! Pokušajte ponovo.')));
        }
    }

    public function export()
    {
        if (empty(session('standard'))) {
            return redirect('/');
        }
        return Excel::download(new CustomerSatisfactionExport, Str::snake(__('Zadovoljstvo korisnika')) . '_' . session('standard_name') . '.xlsx');
    }

    public function deleteColumn($column)
    {
        $customerSat = CustomerSatisfaction::where('team_id', Auth::user()->current_team_id)->get();
        if (!$customerSat->count()) {
            return back()->with('status', array('info', __('Kolona je uspešno uklonjena i svi zapisi su obrisani')));
        }
        $this->authorize('delete', $customerSat[0]);

        $column = 'col' . $column;
        try {
            $customerSat = CustomerSatisfaction::where('team_id', Auth::user()->current_team_id)->update([$column => null]);
            SatisfactionColumn::where([
                ['team_id', Auth::user()->current_team_id],
                ['column_name', $column]
            ])->update(['name' => null]);
            CustomLog::info('Kolona "' . $column . '" uklonjena, ' . Auth::user()->name . ', ' . Auth::user()->username . ', ' . date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', __('Kolona je uspešno uklonjena i svi zapisi su obrisani')));
        } catch (Exception $e) {
            CustomLog::warning('Neuspeli pokušaj brisanja kolone ' . $column . ', ' . Auth::user()->name . ', ' . Auth::user()->username . ', ' . date('d.m.Y H:i:s') . ', Greška: ' . $e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', __('Došlo je do greške! Pokušajte ponovo.')));
        }
    }

    public function print($id)
    {
        $cs = CustomerSatisfaction::with('user')->findOrFail($id);
        $this->authorize('view', $cs);
        $poll = SatisfactionColumn::where('team_id', Auth::user()->current_team_id)->whereNotNull('name')->get();
        $cs->columns = $poll;
        $cs->average = round($cs->average(), 1);
        return view('system_processes.customer_satisfaction.print', compact('cs'));
    }

    public function printAll()
    {
        $cs = CustomerSatisfaction::where([
            ['standard_id', session('standard')],
            ['team_id', Auth::user()->current_team_id]
        ])->get();
        $poll = SatisfactionColumn::where('team_id', Auth::user()->current_team_id)->whereNotNull('name')->get();
        $this->authorize('view', $cs[0]);
        return view('system_processes.customer_satisfaction.print-all', compact('cs', 'poll'));
    }
}
