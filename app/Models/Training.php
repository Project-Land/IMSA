<?php

namespace App\Models;

use App\Models\Document;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Training extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }
    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }

    public function standard()
    {
        return $this->belongsTo('App\Models\Standard');
    }

    // public function documents()
    // {
    //     return $this->hasMany(Document::class);
    // }

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'training_user')->withPivot('document_id')->withTimestamps();
    }

    public function documents()
    {
        return $this->belongsToMany('App\Models\Document', 'training_user')->withPivot('user_id')->withTimestamps();
    }

    public function usersWithoutDocument()
    {
        return $this->belongsToMany('App\Models\User', 'training_user')->wherePivot('document_id',null)->withTimestamps();
    }
}
