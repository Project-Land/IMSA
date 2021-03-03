<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soa extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function standard()
    {
        return $this->belongsTo('App\Models\Standard');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }

    public function documents()
    {
        return $this->belongsToMany('App\Models\Document');
    }

    public function soaField()
    {
        return $this->BelongsTo('App\Models\SoaField');
    }

}
