<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalCheckReport extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function internalCheck(){
        return $this->hasOne('App\Models\InternalCheck');
    }

    public function recommendations()
    {
        return $this->hasMany('App\Models\Recommendation');
    }

    public function inconsistencies()
    {
        return $this->hasMany('App\Models\Inconsistency');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }
}
