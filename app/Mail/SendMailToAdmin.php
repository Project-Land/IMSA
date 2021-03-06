<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMailToAdmin extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $standard_name;
    public $standard_id;
    public $url;
    public $msg;

    public function __construct($not)
    {
        $this->msg = $not->message;
        $this->standard_name = $not->notifiable->standard->name;
        $this->standard_id = $not->notifiable->standard->id;
        $type = strtolower(substr($not->notifiable_type, strrpos($not->notifiable_type, '\\') + 1));

        if ($type == 'goal' || $type == 'supplier' || $type == 'complaint') {
            $this->url = "http://quality4.me/{$type}s?standard={$this->standard_id}&standard_name={$this->standard_name}#{$type}{$not->notifiable_id}";
        } else if ($type == 'internalcheck') {
            $this->url = "http://quality4.me/internal-check?standard={$this->standard_id}&standard_name={$this->standard_name}#{$type}{$not->notifiable_id}";
        } else if ($type == 'measuringequipment') {
            $this->url = "http://quality4.me/measuring-equipment?standard={$this->standard_id}&standard_name={$this->standard_name}#{$type}{$not->notifiable_id}";
        } else if ($type == 'correctivemeasure') {
            $this->url = "http://quality4.me/corrective-measures?standard={$this->standard_id}&standard_name={$this->standard_name}#{$type}{$not->notifiable_id}";
        } else {
            $this->url = "http://quality4.me";
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Obaveštenje')->markdown('emails.admin.notify');
    }
}
