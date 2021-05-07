<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskManagement extends Model
{
    use HasFactory;

    protected $table = "risk_managements";

    protected $guarded = [];

    public static function getStats($teamId, $standardId, $year)
    {
        $rm_total = RiskManagement::where('team_id', $teamId)->where('standard_id', $standardId)->count();
        $rm_closed =RiskManagement::where('team_id', $teamId)->where('standard_id', $standardId)->where('status', 0)->count();

        if($rm_total == 0){
            $rm_percentage = 0;
        }
        else{
            $rm_percentage = ($rm_closed / $rm_total) * 100;
        }
        return __("Zatvoreno")." ".$rm_closed." ".__("mera od ukupno")." ".$rm_total.", ".__("što čini")." ".round($rm_percentage)."%";;
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }

    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }

    public function standard()
    {
        return $this->belongsTo('App\Models\Standard');
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }

    public function notification()
    {
        return $this->morphOne('App\Models\InstantNotification', 'notifiable');
    }
}
