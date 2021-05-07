<?php

namespace App\Models;

use App\Models\Sector;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InternalCheck extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts= [
        'sectors' => 'array',
    ];
    

    public function standard()
    {
        return $this->belongsTo('App\Models\Standard');
    }

    public function planIp()
    {
        return $this->belongsTo('App\Models\PlanIp');
    }

    public function internalCheckReport()
    {
        return $this->belongsTo('App\Models\InternalCheckReport');
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

    public function sector()
    {
        return $this->belongsTo('App\Models\Sector');
    }
    public function sectorNames()
    {
        $sec= Sector::find($this->sectors)->pluck('name');
        $sec=$sec->implode(', ');
        return $sec;
    }
}
