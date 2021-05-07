<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sector extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'team_id', 'user_id', 'is_global'];

    public function correctiveMeasure()
    {
        return $this->belongsTo('App\Models\CorrectiveMeasure');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }

    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }

    public function internalChecks()
    {
        return $this->hasMany('App\Models\InternalCheck');
    }

    public function complaints()
    {
        return $this->hasMany('App\Models\Complaint');
    }
}
