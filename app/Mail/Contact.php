<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Contact extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $company;
    public $email;
    public $date;
    public $message;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->name=$request;//$request->name;
        $this->company='Google';//$request->company;
        $this->email='dadsa@fd.com';//$request->email;
        $this->date='12.10.2021. 14:00';//$request->date;
        $this->message='poruka';//$request->message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.contact');
    }
}
