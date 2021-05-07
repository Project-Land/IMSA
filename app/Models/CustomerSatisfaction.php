<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerSatisfaction extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    protected $table = "customer_satisfaction";

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

    public function average()
    {
        $sum = 0;
        $count = 0;
        for($i=1; $i<=10; $i++){
            if($this->{'col'.$i} != null){
                $sum += $this->{'col'.$i};
                $count++;
            }
        }
        return round($sum/$count, 1);
    }

    public function columnCount($col)
    {
        $count = self::where('team_id', Auth::user()->current_team_id)->whereNotNull($col)->count();
        $count = ($count == 0) ? 1 : $count;
        return $count;
    }
}
