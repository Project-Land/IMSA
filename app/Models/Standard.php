<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Standard extends Model
{
    use HasFactory;

    public function goal()
    {
        return $this->hasOne('App\Models\Goal');
    }

    public function internalCheck()
    {
        return $this->hasMany('App\Models\InternalCheck');
    }

    public function correctiveMeasure()
    {
        return $this->belongsTo('App\Models\CorrectiveMeasure');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function teams()
    {
        return $this->belongsToMany('App\Models\Team');
    }

   
}
