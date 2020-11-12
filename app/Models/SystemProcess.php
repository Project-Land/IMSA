<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemProcess extends Model
{
    use HasFactory;

    public function standards()
    {
        return $this->belongsToMany('App\Models\Standard');
    }
}
