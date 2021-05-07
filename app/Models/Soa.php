<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Soa extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded=[];

    public function standard()
    {
        return $this->belongsTo('App\Models\Standard');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }

    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }

    public function documents()
    {
        return $this->belongsToMany('App\Models\Document')->withPivot('id');
    }

    public function soaField()
    {
        return $this->belongsTo('App\Models\SoaField');
    }

}
