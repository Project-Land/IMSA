<?php

namespace App\Http\Controllers;

use Exception;
use App\Facades\CustomLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ManagementSystemReview;
use App\Http\Requests\ManagementSystemReviewRequest;

class ManagementSystemReviewsController extends Controller
{

    public function index()
    {
        $standardId = session('standard');
        if($standardId == null){
            return redirect('/')->with('status', 'Izaberite standard!');
        }

        $msr = ManagementSystemReview::where([
                ['standard_id', $standardId],
                ['team_id',Auth::user()->current_team_id]
            ])->get();

        return view('system_processes.management_system_reviews.index', compact('msr'));
    }

    public function getData(Request $request) {
        $standardId = session('standard');
        if($standardId == null){
            return redirect('/')->with('status', 'Izaberite standard!');
        }
        
        $reviews = ManagementSystemReview::where([
                ['standard_id', $standardId],
                ['year', $request->data['year']]
            ])->get();

        $isAdmin = Auth::user()->allTeams()->first()->membership->role == "admin" || Auth::user()->allTeams()->first()->membership->role == "super-admin" ? true : false;

        if(!$reviews->isEmpty()){
            $reveiws = $reviews->map(function ($item, $key) use ($isAdmin){
                $item->isAdmin = $isAdmin;
                return $item;
            });
        }

        return response()->json($reviews);
    }

    public function create()
    {
        $this->authorize('create', ManagementSystemReview::class);
        return view('system_processes.management_system_reviews.create');
    }

    public function store(ManagementSystemReviewRequest $request)
    {
        $this->authorize('create', ManagementSystemReview::class);

        try{
            $msr = ManagementSystemReview::Create($request->all());
            CustomLog::info('Zapisnik sa preispitivanja "'.$msr->year.'" kreiran, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Zapisnik je uspešno sačuvan!');
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja zapisnika sa preispitivanja '.$msr->year.', '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/management-system-reviews');
    }

    public function show(int $id)
    {
        if(!request()->expectsJson()){
            abort(404);
        }
        
        $msr = ManagementSystemReview::findOrFail($id);
        return response()->json($msr);
    }

    public function edit(int $id)
    {
        $msr = ManagementSystemReview::findOrFail($id);
        $this->authorize('update', $msr);
        
        return view('system_processes.management_system_reviews.edit', compact('msr'));
    }

    public function update(ManagementSystemReviewRequest $request, int $id)
    {
        $msr = ManagementSystemReview::findOrFail($id);
        $this->authorize('update', $msr);

        try{
            $msr->update($request->all());
            CustomLog::info('Zapisnik sa preispitivanja "'.$msr->year.'" izmenjen, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Zapisnik je uspešno izmenjen!');
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene zapisnika sa preispitivanja "'.$msr->year.'", '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
            $request->session()->flash('status', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/management-system-reviews');
    }

    public function destroy(int $id)
    {
        $this->authorize('delete', ManagementSystemReview::find($id));
        $msr = ManagementSystemReview::findOrFail($id);

        try{
            ManagementSystemReview::destroy($id);
            CustomLog::info('Zapisnik sa preispitivanja "'.$msr->year.'" uklonjen, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
            return back()->with('status', 'Zapisnik je uspešno uklonjen');
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja zapisnika sa preispitivanja "'.$msr->year.'", '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
            return back()->with('status', 'Došlo je do greške! Pokušajte ponovo.');
        }
    }

    public function deleteApi($id)
    {
        $msr = ManagementSystemReview::findOrFail($id);
        $this->authorize('delete', $msr);

        try{
            ManagementSystemReview::destroy($id);
            CustomLog::info('Zapisnik sa preispitivanja "'.$msr->year.'" uklonjen, '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s'), \Auth::user()->currentTeam->name);
            return true;
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja zapisnika sa preispitivanja "'.$msr->year.'", '.\Auth::user()->name.', '.\Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška: '.$e->getMessage(), \Auth::user()->currentTeam->name);
            return false;
        }
    }
}
