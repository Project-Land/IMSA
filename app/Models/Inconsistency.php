<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inconsistency extends Model
{
    use HasFactory;

    public function report()
    {
        return $this->belongsTo('App\Models\InternalCheckReport');
    }

    public function correctiveMeasure()
    {
        return $this->hasOne('App\Models\CorrectiveMeasure');
    }
}
