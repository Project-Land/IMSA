<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function standard()
    {
        return $this->hasOne('App\Models\Standard');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }

    public static function getStats($teamId, $standardId, $year)
    {
        $os_total = Goal::where('team_id', $teamId)->where('standard_id', $standardId)->where('year', $year)->count();
        $os_fulfilled = Goal::where('team_id', $teamId)->where('standard_id', $standardId)->where('year', $year)->whereNotNull('analysis')->count();
        if($os_total == 0){
            $os_percentage = 0;
        }
        else{
            $os_percentage = ($os_fulfilled / $os_total) * 100;
        }
        return "Ostvareno ".$os_fulfilled." ciljeva od ukupno ".$os_total." što čini ".round($os_percentage)."%";
    }

    public function notification()
    {
        return $this->morphOne('App\Models\Notification', 'notifiable');
    }
}
