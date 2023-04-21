<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserRegistration extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    
    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        $from = 'info@radiologycheck.com';
        $name = 'Radiology';
        $subject = 'Get the Best and Fastest Radiology Second Opinion';
        return $this->markdown('emails.send-welcome-mail')->from($from, $name)->subject($subject)
                    ->with(['user' => $this->user]);
    }
}
