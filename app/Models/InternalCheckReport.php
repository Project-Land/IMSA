<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InternalCheckReport extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function internalCheck()
    {
        return $this->hasOne('App\Models\InternalCheck');
    }

    public function recommendations()
    {
        return $this->hasMany('App\Models\Recommendation');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }
    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }

    /*public function correctiveMeasures()
    {
        return $this->hasMany('App\Models\CorrectiveMeasure');
    } */
    public function correctiveMeasures()
    {
        return $this->morphMany('App\Models\CorrectiveMeasure', 'correctiveMeasureable');
    }
}
