<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeasuringEquipment extends Model
{
    use HasFactory;

    public function notification()
    {
        return $this->morphOne('App\Models\Notification', 'notifiable');
    }

}
