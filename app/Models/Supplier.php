<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function standard()
    {
        return $this->belongsTo('App\Models\Standard');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }

    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }

    public function notification()
    {
        return $this->morphOne('App\Models\Notification', 'notifiable');
    }

    public static function getStats($teamId, $standardId, $year)
    {
        $sup_total = Supplier::where('team_id', $teamId)->where('standard_id', $standardId)->whereYear('created_at', $year)->count();
        $sup_approved = Supplier::where('team_id', $teamId)->where('standard_id', $standardId)->whereYear('created_at', $year)->where('status', '1')->count();;

        if($sup_approved == 0){
            $sup_percentage = 0;
        }
        else{
            $sup_percentage = ($sup_approved / $sup_total) * 100;
        }

        return __("Odobreno")." ".$sup_approved." ".__("isporučilaca od ukupno")." ".$sup_total.", ".__("što čini")." ".round($sup_percentage)."%";
    }
}
