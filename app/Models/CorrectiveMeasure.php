<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorrectiveMeasure extends Model
{
    use HasFactory;

    public function standard()
    {
        return $this->belongsTo('App\Models\Standard');
    }

    public function sector()
    {
        return $this->belongsTo('App\Models\Sector');
    }
}
