<?php

namespace App\Http\Controllers;

use App\Models\Standard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function getStandard()
    {
        $standardId = session('standard');
        if(isset($standardId)) {
            $standard = Standard::find($standardId);
            return $standard->id;
        } else {
            return null;
        }
    }

    public static function getCompanyName()
    {
        $company = Auth::user()->currentTeam->name;
        return $company;
    }
}
