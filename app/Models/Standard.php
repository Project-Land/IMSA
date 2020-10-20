<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Standard extends Model
{
    use HasFactory;

    public function goal(){
        return $this->hasOne('App\Models\Goal');
    }

    public function internalCheck(){
        return $this->hasOne('App\Models\InternalCheck');
    }

    public function correctiveMeasure(){
        return $this->belongsTo('App\Models\CorrectiveMeasure');
    }
   
}
