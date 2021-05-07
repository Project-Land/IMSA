<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'team_id', 'user_id', 'is_global'];

    public function correctiveMeasure()
    {
        return $this->belongsTo('App\Models\CorrectiveMeasure');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
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
