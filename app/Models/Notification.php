<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Notification extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }

    public function scopeActive($query)
    {
       // return $query->where('checkTime', '<', Carbon::now()->addDays(7))->where('checkTime', '>',Carbon::now())->where('team_id',///Auth::user()->current_team_id);
       return $query->where([['checkTime', '<', Carbon::now()->addDays(7)],['checkTime', '>',Carbon::now()],['team_id',Auth::user()->current_team_id]])->orWhere([['notifiable_type','App\Models\MeasuringEquipment'
    ],['checkTime', '<', Carbon::now()->addDays(15)],['checkTime', '>',Carbon::now()],['team_id',Auth::user()->current_team_id]]);
    }

    public function scopeActiveInternalChecks($query)
    {
        return $query->where('checkTime', '<', Carbon::now()->addDays(7))->where('checkTime', '>',Carbon::now())->where('notifiable_type','App\Models\InternalCheck')->where('team_id',Auth::user()->current_team_id);
    }

    public function notifiable()
    {
        return $this->morphTo();
    }
    
}
