<?php

namespace App\Http\Controllers;


use App\Mail\Contact;
use App\Models\Standard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class HomeController extends Controller
{
    public function index()
    {
        if (!session()->has('locale')) {
            session(['locale' => 'sr']);
        }

        $standard = session('standard') ?? 1;
        return redirect('/standards/' . $standard);
    }

    public function standard($id)
    {
        session()->forget('standard');
        session()->forget('standard_name');
        try {
            $teamId = Auth::user()->current_team_id;
            $standard = Standard::whereHas('teams', function ($q) use ($teamId) {
                $q->where('team_id', $teamId);
            })->findOrFail($id);

            session(['standard' => $id]);
            session(['standard_name' => $standard->name]);
            return view('standard', compact('standard'));
        } catch (ModelNotFoundException $e) {
            return redirect('/')->with('status', array('secondary', __('Izaberite standard!')));
        }
    }

    public function analytics()
    {
        $role = Auth::user()->allTeams()->first()->membership->role;
        if ($role == "super-admin") {
            return file_get_contents(storage_path('log.html'));
        } else abort(404);
    }

    public function document_download(Request $request)
    {
        $folder = $request->folder;
        $file_name = $request->file_name;

        $path = storage_path() . '/' . 'app' . '/' . $folder . '/' . $file_name;

        if (file_exists($path)) {
            return Response::download($path);
        } else {
            return back()->with('status', array('danger', __('Fajl nije pronađen')));
        }
    }

    public function document_preview(Request $request)
    {
        $folder = $request->folder;
        $file_name = $request->file_name;

        $path = storage_path() . '/' . 'app' . '/' . $folder . '/' . $file_name;

        if (file_exists($path)) {
            return Response::file($path);
        } else {
            return back()->with('status', array('danger', __('Fajl nije pronađen')));
        }
    }

    public function about()
    {
        return redirect('/images/imsa.pdf');
    }

    public function contact()
    {
        return view('guest.contact');
    }

    public function manual()
    {
        return redirect('/images/uputstvo_za_korišćenje.pdf');
    }


    public function lang($lang)
    {
        session(['locale' => $lang]);
        $a=url()->previous();
        if(!strpos($a, "contact")){
            return back();
        }
        $pos=strpos($a, "?") ? strpos($a, "?"):50;
        $a= substr($a, 0, $pos);
        $a=$a.'?lang='.$lang;
    
        return redirect($a);//back();
    }

    public function markAsRead($id)
    {
        $not = Auth::user()->instant_notification()->where('instant_notification_id', $id)->first();
        $not->pivot->is_read = 1;
        $not->pivot->save();
        return back();
    }

    public function deleteNotification($id)
    {
        Auth::user()->instant_notification()->where('instant_notification_id', $id)->delete();
        return back();
    }

    public function contactWithEmail(Request $request)
    {

        // return new Contact($request);
        Mail::to('msretic@projectland.rs')->send(new Contact($request));

        return back()->with(['message' => __('Poruka je uspešno prosleđena, odgovorićemo vam u najkraćem roku')]);
    }
}
