<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

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

    public function training()
    {
        return $this->belongsTo('App\Models\Training');
    }
}
