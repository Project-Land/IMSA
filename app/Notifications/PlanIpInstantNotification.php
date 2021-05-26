<?php

namespace App\Notifications;

use App\Models\PlanIp;
use App\Notifications\InstantNotification;

class PlanIpInstantNotification extends InstantNotification
{
    protected $table = "instant_notifications";

    public function __construct(PlanIp $planIp)
    {
        $this->attributes['notifiable_id'] = $planIp->id;
        $this->attributes['notifiable_type'] = "App\Models\PlanIp";
        $this->attributes['message'] = __('Kreiran je novi plan interne provere') . ': ' . $planIp->name;
        $this->attributes['data'] = "/internal-check#internalcheck" . $planIp->internalCheck->id;
    }
}
