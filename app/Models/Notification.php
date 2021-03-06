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
       return $query->where([
           ['checkTime', '<', Carbon::now()->addDays(7)],
           ['checkTime', '>', Carbon::now()],
           ['team_id', Auth::user()->current_team_id],
        ])->whereHasMorph('notifiable',  ['App\Models\InternalCheck', 'App\Models\Goal', 'App\Models\Supplier','App\Models\Complaint','App\Models\CorrectiveMeasure'], function ($q){
            $q->where('standard_id', session('standard'));
        })
        ->orWhere([
            ['notifiable_type','App\Models\MeasuringEquipment'],
            ['checkTime', '<', Carbon::now()->addDays(15)],
            ['checkTime', '>', Carbon::now()],
            ['team_id', Auth::user()->current_team_id]
        ])->whereHasMorph('notifiable',  ['App\Models\MeasuringEquipment'], function ($q){
            $q->where('standard_id', session('standard'));
        });
    }

    public function scopeActiveInternalChecks($query)
    {
        return $query->where([
            ['checkTime', '<', Carbon::now()->addDays(7)],
            ['checkTime', '>', Carbon::now()],
            ['notifiable_type', 'App\Models\InternalCheck'],
            ['team_id', Auth::user()->current_team_id]
        ])->whereHasMorph('notifiable',  ['App\Models\InternalCheck'], function ($q){
            $q->where('standard_id', session('standard'));
        });
    }

    public function scopeUserNots($query)
    {
        $instantNots = Auth::user()->instant_notification;

        if(!$instantNots->count()){
            return $query->where('id', '-1');
        }

        $query->where([
            ['checkTime', '<', Carbon::now()->addDays(7)],
            ['checkTime', '>', Carbon::now()],
            ['team_id', Auth::user()->current_team_id]
        ]);

        foreach($instantNots as $inst){
            $query->where('notifiable_type', $inst->notifiable_type)->where('notifiable_id', $inst->notifiable_id)
            ->whereHasMorph('notifiable',  [$inst->notifiable_type], function ($q){
                $q->where('standard_id', session('standard'));
            });;
        }

        return $query;
    }

    public function notifiable()
    {
        return $this->morphTo();
    }

}
