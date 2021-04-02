<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Sector;
use App\Models\Document;
use App\Models\Complaint;
use App\Facades\CustomLog;
use Illuminate\Support\Str;
use App\Models\Notification;
use App\Exports\ComplaintsExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ComplaintsRequest;
use App\Notifications\InstantNotification;
use App\Notifications\ComplaintInstantNotification;

class ComplaintsController extends Controller
{

    public function index()
    {
        if(request()->has('standard') && request()->has('standard_name')){
            session(['standard' => request()->get('standard')]);
            session(['standard_name' => request()->get('standard_name')]);

        }
        if(session('standard') == null || session('standard_name') != "9001"){
            return redirect('/');
        }

        $complaints = Complaint::where([
                ['standard_id', session('standard')],
                ['team_id', Auth::user()->current_team_id]
            ])->with('sector')->get();

        return view('system_processes.complaints.index', compact('complaints'));
    }

    public function create()
    {
        if(session('standard') == null || session('standard_name') != "9001"){
            return redirect('/');
        }

        $sectors = Sector::where([
            ['team_id', Auth::user()->current_team_id]
        ])->get();

        $users = User::with('teams')->where('current_team_id', Auth::user()->current_team_id)->get();

        $this->authorize('create', Complaint::class);
        return view('system_processes.complaints.create', compact('sectors', 'users'));
    }

    public function store(ComplaintsRequest $request)
    {
        $this->authorize('create', Complaint::class);

        try{
            $complaint = Complaint::create($request->except(['file', 'responsible_person']));
            $complaint->users()->sync($request->responsible_person);

            if($request->deadline_date){
                $notification = Notification::create([
                    'message' => __('Rok za realizaciju reklamacije ').date('d.m.Y', strtotime($complaint->deadline_date)),
                    'team_id' => Auth::user()->current_team_id,
                    'checkTime' => $complaint->deadline_date
                ]);
                $complaint->notification()->save($notification);

                $not = new ComplaintInstantNotification($complaint);
                $not->save();
                $not->user()->sync($request->responsible_person);
            }

            if($request->file('file')){
                foreach($request->file('file') as $file){
                    $file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME).time();
                    $name = $complaint->name;
                    $file_name = $file_name.".".$file->getClientOriginalExtension();
                    $path = $file->storeAs(Str::snake($this::getCompanyName())."/complaint",  $file_name);
                    $document = Document::create([
                        'standard_id' => $complaint->standard_id,
                        'team_id' => $complaint->team_id,
                        'user_id' => $complaint->user_id,
                        'document_name' => $name,
                        'version' => 1,
                        'file_name' => $file_name,
                        'doc_category' => 'complaint'
                    ]);
                    $complaint->documents()->save($document);
                }
            }

            CustomLog::info('Reklamacija "'.$complaint->name.'" kreirana, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', __('Reklamacija je uspešno sačuvana!')));

        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj kreiranja reklamacije, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška- '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', __('Došlo je do greške, pokušajte ponovo!')));
        }
        return redirect('/complaints');
    }

    public function show($id)
    {
        $complaint = Complaint::with('user', 'sector', 'users')->findOrFail($id);
        return $complaint;
    }

    public function edit($id)
    {
        $this->authorize('update', Complaint::find($id));

        $sectors = Sector::where([
            ['team_id', Auth::user()->current_team_id]
        ])->get();

        $complaint = Complaint::findOrFail($id);

        $users = User::with('teams')->where('current_team_id', Auth::user()->current_team_id)->get();
        return view('system_processes.complaints.edit', compact('complaint', 'sectors', 'users'));
    }

    public function update(ComplaintsRequest $request, $id)
    {
        $this->authorize('update', Complaint::find($id));

        $complaint = Complaint::findOrFail($id);

        try{

            $files = $request->file ?? [];
            foreach($complaint->documents()->pluck('document_id')->diff($files) as $docId){
                $doc = Document::find($docId);
                Storage::delete(Str::snake($this->getCompanyName()).'/complaint/'.$doc->file_name);
                $complaint->documents()->wherePivot('document_id', $docId)->detach();
                $doc->forceDelete();
            }

            $complaint->update($request->except(['file', 'new_file', 'responsible_person']));
            $complaint->users()->sync($request->responsible_person);

            if($request->deadline_date){
                $notification = $complaint->notification;
                if(!$notification){
                    $notification = new Notification();
                    $notification->team_id = Auth::user()->current_team_id;
                }
                $notification->message = __('Rok za realizaciju reklamacije ').date('d.m.Y', strtotime($request->deadline_date));
                $notification->checkTime = $complaint->deadline_date;
                $complaint->notification()->save($notification);

                $oldNot = InstantNotification::where('notifiable_id', $complaint->id)->where('notifiable_type', 'App\Models\Complaint')->get();
                if($oldNot->count()){
                    $oldNot[0]->user()->sync($request->responsible_person);
                }
                else{
                    $not = new ComplaintInstantNotification($complaint);
                    $not->save();
                    $not->user()->sync($request->responsible_person);
                }
            } else{
                if($complaint->notification){
                    $complaint->notification->delete();
                }
            }

            if($request->file('new_file')){
                foreach($request->file('new_file') as $file){
                    $file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME).time();
                    $name = $complaint->name;
                    $file_name = $file_name.".".$file->getClientOriginalExtension();
                    $path = $file->storeAs(Str::snake($this::getCompanyName())."/complaint",  $file_name);
                    $document = Document::create([
                        'standard_id' => $complaint->standard_id,
                        'team_id' => $complaint->team_id,
                        'user_id' => $complaint->user_id,
                        'document_name' => $name,
                        'version' => 1,
                        'file_name' => $file_name,
                        'doc_category' => 'complaint'
                    ]);
                    $complaint->documents()->save($document);
                }
            }

            CustomLog::info('Reklamacija "'.$complaint->name.'" izmenjena, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('info', __('Reklamacija je uspešno izmenjena!')));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj izmene reklamacije "'.$complaint->name.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška- '.$e->getMessage(), Auth::user()->currentTeam->name);
            $request->session()->flash('status', array('danger', __('Došlo je do greške, pokušajte ponovo!')));
        }
        return redirect('/complaints');
    }

    public function destroy($id)
    {
        $this->authorize('delete', Complaint::find($id));
        $complaint = Complaint::findOrFail($id);

        try{
            foreach($complaint->documents()->pluck('document_id') as $docId){
                $doc=Document::find($docId);
                Storage::delete(Str::snake($this->getCompanyName()).'/complaint/'.$doc->file_name);
                $complaint->documents()->wherePivot('document_id',$docId)->detach();
                $doc->forceDelete();
            }
            $complaint->notification()->delete();
            InstantNotification::where('notifiable_id', $complaint->id)->where('notifiable_type', 'App\Models\Complaint')->delete();

            Complaint::destroy($id);
            CustomLog::info('Reklamacija "'.$complaint->name.'" uklonjena, '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s'), Auth::user()->currentTeam->name);
            return back()->with('status', array('info', __('Reklamacija je uspešno obrisana')));
        } catch(Exception $e){
            CustomLog::warning('Neuspeli pokušaj brisanja reklamacije "'.$complaint->name.'", '.Auth::user()->name.', '.Auth::user()->username.', '.date('d.m.Y H:i:s').', Greška- '.$e->getMessage(), Auth::user()->currentTeam->name);
            return back()->with('status', array('danger', __('Došlo je do greške! Pokušajte ponovo.')));
        }
    }

    public function export()
    {
        if(empty(session('standard'))){
            return redirect('/');
        }
        return Excel::download(new ComplaintsExport, Str::snake(__('Reklamacije')).'_'.session('standard_name').'.xlsx');
    }

    public function print($id)
    {
        $complaint = Complaint::with('user','standard','sector')->findOrFail($id);
        $this->authorize('view', $complaint);
        return view('system_processes.complaints.print', compact('complaint'));

    }
}
