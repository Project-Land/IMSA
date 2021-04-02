<?php

namespace App\Notifications;

use App\Models\Complaint;
use App\Notifications\InstantNotification;

class ComplaintInstantNotification extends InstantNotification
{
    protected $table = "instant_notifications";

    public function __construct(Complaint $complaint)
    {
        $this->attributes['notifiable_id'] = $complaint->id;
        $this->attributes['notifiable_type'] = "App\Models\Complaint";
        $this->attributes['message'] = __('Dodati ste kao lice odgovorno za rešavanje reklamacije').': '.$complaint->name;
        $this->attributes['data'] = "/complaints#complaint".$complaint->id;
    }
}
