<?php

namespace App\Notifications;

use App\Models\RiskManagement;
use App\Notifications\InstantNotification;

class RiskInstantNotification extends InstantNotification
{
    protected $table = "instant_notifications";

    public function __construct(RiskManagement $riskManagement)
    {
        $this->attributes['notifiable_id'] = $riskManagement->id;
        $this->attributes['notifiable_type'] = "App\Models\RiskManagement";
        $this->attributes['message'] = "Dodata odgovornost za: ".$riskManagement->measure;
        $this->attributes['data'] = "/risk-management#riskmanagement".$riskManagement->id;
    }
}
