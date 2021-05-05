<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationOfLegalAndOtherRequirement extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function correctiveMeasures()
    {
        return $this->morphMany('App\Models\CorrectiveMeasure', 'correctiveMeasureable');
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

    public static function getStats($teamId, $standardId, $year)
    {
        $total = EvaluationOfLegalAndOtherRequirement::where('team_id', $teamId)->where('standard_id', $standardId)->whereYear('created_at', $year)->count();
        $agreed = EvaluationOfLegalAndOtherRequirement::where('team_id', $teamId)->where('standard_id', $standardId)->whereYear('created_at', $year)->whereCompliance(1)->count();
        if($total == 0){
            $percentage = 0;
        }
        else{
            $percentage = ($agreed / $total) * 100;
        }
        return __("Prepoznato je")." ".$total." ".__("zahteva od čega je")." ".$agreed." ".__("usaglašeno, što čini")." ".round($percentage)."%";
    }
}
