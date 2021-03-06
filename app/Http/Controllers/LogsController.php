<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class LogsController extends Controller
{
    public function show(Request $request, $company)
    {
        $filePath = storage_path("logs/".$company.".log");
        $data = []; $logs = []; $result = []; $allLogs = [];

        if(File::exists($filePath)) {
            $data = [
                'lastModified' => new Carbon(File::lastModified($filePath)),
                'size' => File::size($filePath),
                'file' => File::get($filePath)
            ];

            foreach (explode("\n", $data['file']) as $key => $line){
                $allLogs[$key] = $line;
            }

            foreach($allLogs as $key => $log){
                if(!empty($log)){
                    $result[$key] = explode(", ", substr(strstr($log, " "), 1));

                    $logs[$key] = [
                        'status' => Str::after(strtok($result[$key][0], ":"), '.'),
                        'message' => substr(strstr($result[$key][0], ":"), 1),
                        'user' => $result[$key][1] . ', ' . $result[$key][2],
                        'time' => $result[$key][3]
                    ];

                    if($logs[$key]['status'] == "WARNING"){
                        $logs[$key] += [
                            'error' => $result[$key][4]
                        ];
                    }
                }
            }

            return view('logs.index', compact('company', 'data', 'logs'));
        }
        else{
            return redirect('teams')->with('status', array('danger', 'Izabrani log ne postoji!'));
        }

    }
}
