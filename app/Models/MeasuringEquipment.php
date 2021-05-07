<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MeasuringEquipment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded=[];

    public function notification()
    {
        return $this->morphOne('App\Models\Notification', 'notifiable');
    }

    public function standard()
    {
        return $this->belongsTo('App\Models\Standard');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }
}
