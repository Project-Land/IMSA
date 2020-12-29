<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnvironmentalAspect extends Model
{

    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
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
        $ea_total = EnvironmentalAspect::where('team_id', $teamId)->where('standard_id', $standardId)->whereYear('created_at', $year)->count();
        $ea_significant = EnvironmentalAspect::where('team_id', $teamId)->where('standard_id', $standardId)->whereYear('created_at', $year)->where('estimated_impact', ">=", 8)->count();
        if($ea_total == 0){
            $ea_percentage = 0;
        }
        else{
            $ea_percentage = ($ea_significant / $ea_total) * 100;
        }
        return __("Prepoznato je")." ".$ea_total." ".__("aspekata od čega je")." ".$ea_significant." ".__("značajnih, što čini")." ".round($ea_percentage)."%";
    }
}
