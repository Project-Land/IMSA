<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoaFieldGroup extends Model
{
    use HasFactory;

    public function soaFields()
    {
        return $this->hasMany('App\Models\SoaField');
    }
}
