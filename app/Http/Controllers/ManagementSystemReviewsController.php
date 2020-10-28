<?php

namespace App\Http\Controllers;

use Exception;
use App\Facades\CustomLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ManagementSystemReview;
use App\Http\Requests\StoreManagementSystemReview;
use App\Http\Requests\UpdateManagementSystemReview;

class ManagementSystemReviewsController extends Controller
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
        $msr = ManagementSystemReview::where([
                ['standard_id', $standardId],
                ['team_id',Auth::user()->current_team_id]
            ])->get();
        return view('system_processes.management_system_reviews.index', compact('msr'));
    }

    public function getData(Request $request) {
        $standardId = $this::getStandard();
        if($standardId == null){
            return redirect('/');
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', ManagementSystemReview::class);
        return view('system_processes.management_system_reviews.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreManagementSystemReview $request)
    {
        $this->authorize('create', ManagementSystemReview::class);
        try{
            $msr = ManagementSystemReview::Create($request->all());
            CustomLog::info('Zapisnik sa preispitivanja "'.$msr->year.'" kreiran. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
            $request->session()->flash('status', 'Zapisnik je uspešno sačuvan!');
        }catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja zapisnika sa preispitivanja. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s').' Greška- '.$e->getMessage(), 'Firma-'.\Auth::user()->current_team_id);
            $request->session()->flash('status', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/management-system-reviews');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $msr = ManagementSystemReview::findOrFail($id);
        return response()->json($msr);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $msr = ManagementSystemReview::findOrFail($id);
        $this->authorize('update', $msr);
        return view('system_processes.management_system_reviews.edit', compact('msr'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateManagementSystemReview $request, int $id)
    {
        $msr = ManagementSystemReview::findOrFail($id);
        $this->authorize('update', $msr);
        try{
            $msr->update($request->all());
            CustomLog::info('Zapisnik sa preispitivanja "'.$msr->year.'" izmenjen. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
            $request->session()->flash('status', 'Zapisnik je uspešno izmenjen!');
        }catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene zapisnika sa preispitivanja. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s').' Greška- '.$e->getMessage(), 'Firma-'.\Auth::user()->current_team_id);
            $request->session()->flash('status', 'Došlo je do greške, pokušajte ponovo!');
        }
        return redirect('/management-system-reviews');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $this->authorize('delete', ManagementSystemReview::find($id));

        $msr = ManagementSystemReview::findOrFail($id);
        if(ManagementSystemReview::destroy($id)){
            CustomLog::info('Zapisnik sa preispitivanja "'.$msr->year.'" uklonjen. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
            return back()->with('status', 'Zapisnik je uspešno uklonjen');
        } else{
            return back()->with('status', 'Došlo je do greške! Pokušajte ponovo.');
        }
    }

    public function deleteApi($id)
    {
        $msr = ManagementSystemReview::findOrFail($id);
        $this->authorize('delete', $msr);
        ManagementSystemReview::destroy($id);
        CustomLog::info('Zapisnik sa preispitivanja "'.$msr->year.'" uklonjen. Korisnik: '.\Auth::user()->name.', '.\Auth::user()->email.', '.date('d.m.Y').' u '.date('H:i:s'), 'Firma-'.\Auth::user()->current_team_id);
        return true;
    }
}
