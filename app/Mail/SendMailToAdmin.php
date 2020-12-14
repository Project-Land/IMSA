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
     public $standard;
     public $url;
     public $msg;
     public function __construct($not)
    {
        $this->msg=$not->message;
        $this->standard=$not->notifiable->standard->name;
        $type=strtolower(substr($not->notifiable_type, strrpos($not->notifiable_type, '\\') + 1));
       
            if($type=='goal'|| $type=='supplier'){
                // $this->url="http://quality4.me/{$type}s#{$type}{$not->notifiable_id}";
                $this->url="http://imsa.test/{$type}s#{$type}{$not->notifiable_id}";
            }
            else if($type=='internalcheck'){
                $this->url="http://imsa.test/internal-check#{$type}{$not->notifiable_id}";
            }
            else if($type=='measuringequipment'){
                $this->url="http://imsa.test/measuring-equipment#{$type}{$not->notifiable_id}";
            }   
            else{
                $this->url="http://imsa.test";
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
