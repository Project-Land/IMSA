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

    public function inconsistency()
    {
        return $this->belongsTo('App\Models\Inconsistency');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }

    public function internal_check_report()
    {
        return $this->belongsTo('App\Models\InternalCheckReport');
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
        return "Zatvoreno je ".$icm_approved." mera od ukupno ".$icm_total." pokrenutih što čini ".round($icm_percentage)."%";
    }

    public function correctiveMeasureable()
    {
        return $this->morphTo();
    }
}
