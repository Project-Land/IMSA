<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PlanIpCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $planIp;
    public $url;
    public $standard_name;

    public function __construct($planIp, $url, $standard_name)
    {
        $this->planIp = $planIp;
        $this->url = $url;
        $this->standard_name = $standard_name;
    }

    public function build()
    {
        return $this->markdown('emails.admin.planip');
    }
}
