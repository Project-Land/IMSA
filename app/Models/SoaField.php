<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoaField extends Model
{
    use HasFactory;

    public function soas()
    {
        return $this->hasMany('App\Models\Soa');
    }
}
