<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskManagement extends Model
{
    protected $table = "risk_managements";

    use HasFactory;

    public static function getStats($standardId, $year)
    {
        $rm_total = RiskManagement::where('standard_id', $standardId)->whereYear('created_at', $year)->count();;
        $rm_closed =Supplier::where('standard_id', $standardId)->whereYear('created_at', $year)->where('status', 0)->count();;
        if($rm_total == 0){
            $rm_percentage = 0;
        }
        else{
            $rm_percentage = ($rm_closed / $rm_total) * 100;
        }
        return "Zatvoreno ".$rm_closed. " mera od ukupno ".$rm_total.", što čini ".round($rm_percentage)."%";;
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }
}
