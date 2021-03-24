<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }

    public function notification()
    {
        return $this->morphOne('App\Models\Notification', 'notifiable');
    }

    public function standard()
    {
        return $this->belongsTo('App\Models\Standard');
    }

    public function documents()
    {
        return $this->belongsToMany('App\Models\Document');
    }

    public function sector()
    {
        return $this->belongsTo('App\Models\Sector');
    }
}
