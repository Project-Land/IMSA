<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorrectiveMeasure extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function standard()
    {
        return $this->belongsTo('App\Models\Standard');
    }

    public function sector()
    {
        return $this->belongsTo('App\Models\Sector');
    }

    public static function getStats($standardId, $year)
    {
        $icm_total = CorrectiveMeasure::where('standard_id', $standardId)->whereYear('measure_date', $year)->count();
        $icm_approved = CorrectiveMeasure::where('standard_id', $standardId)->whereYear('measure_date', $year)->where('measure_approval', '1')->count();
        if($icm_total == 0){
            $icm_percentage = 0;
        }
        else{
            $icm_percentage = ($icm_approved / $icm_total) * 100;
        }
        return "Odobreno ".$icm_approved." mera od ukupno ".$icm_total." što čini ".round($icm_percentage)."%";
    }
<<<<<<< HEAD
    
=======
>>>>>>> 69e93a62de4d0597820d99a65fbefc8736a0a81f
    public function inconsistency()
    {
        return $this->belongsTo('App\Models\Inconsistency');
    }
}
