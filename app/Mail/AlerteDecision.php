<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AlerteDecision extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $date;
    public $arrets;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$date,$arrets)
    {
        $this->user   = $user;
        $this->date   = $date;
        $this->arrets = $arrets;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.alert');
    }
}
