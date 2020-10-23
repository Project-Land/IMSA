<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    public function sector()
    {
        return $this->belongsTo('App\Models\Sector');
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
