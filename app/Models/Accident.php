<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accident extends Model
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
        $ac_total = Accident::where('team_id', $teamId)->where('standard_id', $standardId)->whereYear('injury_datetime', $year)->count();
        $ac_minor = Accident::where('team_id', $teamId)->where('standard_id', $standardId)->whereYear('injury_datetime', $year)->where('injury_type', "mala")->count();
        $ac_major = Accident::where('team_id', $teamId)->where('standard_id', $standardId)->whereYear('injury_datetime', $year)->where('injury_type', "velika")->count();
        $ac_accident = Accident::where('team_id', $teamId)->where('standard_id', $standardId)->whereYear('injury_datetime', $year)->where('injury_type', "incident")->count();

        return __("Utvrđeno je")." ".$ac_total." ".__("povreda na radu od čega je")." ".$ac_minor." ".__("malih, ")." ".$ac_major." ".__("velikih i ")." ".$ac_accident." ".__("incidenata bez povrede");
    }

}
