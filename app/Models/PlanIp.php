<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanIp extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function internalCheck(){
        return $this->hasOne('App\Models\InternalCheck');
    }

    public static function getStats($standardId, $year)
    {
        $cr_total = PlanIp::where('standard_id', $standardId)->whereYear('check_start', $year)->count();
        $cr_finished = PlanIp::where('standard_id', $standardId)->whereYear('check_end', $year)->count();
        if($cr_total == 0){
            $cr_percentage = 0;
        }
        else{
            $cr_percentage = ($cr_finished / $cr_total) * 100;
        }
        return "Završeno ".$cr_finished. " provera od ukupno ".$cr_total." zakazanih, što čini ".round($cr_percentage)."%";
    }
}
