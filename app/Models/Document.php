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

    public function soas()
    {
        return $this->belongsToMany('App\Models\Soa');
    }

    public function complaints()
    {
        return $this->belongsToMany('App\Models\Complaint');
    }

    public function trainings()
    {
        return $this->belongsToMany('App\Models\Training', 'training_user')->withPivot('user_id')->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'training_user')->withPivot('training_id')->withTimestamps();
    }
}
