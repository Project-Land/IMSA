<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalCheck extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function standard(){
        return $this->belongsTo('App\Models\Standard');
    }

    public function planIp(){
        return $this->belongsTo('App\Models\PlanIp');
    }

    public function internalCheckReport(){
        return $this->belongsTo('App\Models\InternalCheckReport');
    }
}
