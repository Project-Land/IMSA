<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamStats extends Model
{
    use HasFactory;

    protected $table = "team_stats";

    public function team()
    {
        return $this->hasOne('App\Models\Team', 'id', 'team_id');
    }
}
