<?php

namespace App\Notifications;

use App\Models\Goal;
use App\Notifications\InstantNotification;

class GoalInstantNotification extends InstantNotification
{
    protected $table = "instant_notifications";

    public function __construct(Goal $goal)
    {
        $this->attributes['notifiable_id'] = $goal->id;
        $this->attributes['notifiable_type'] = "App\Models\Goal";
        $this->attributes['message'] = __('Dodati ste kao lice odgovorno za praćenje i realizaciju cilja').': '.$goal->goal;
        $this->attributes['data'] = "/goals#goal".$goal->id;
    }
}
