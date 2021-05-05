<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagementSystemReview extends Model
{
    protected $table = "management_system_reviews";

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
}
