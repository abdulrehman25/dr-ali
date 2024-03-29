<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Package;

class RadiologyFinalReport extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    
    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
		$site_url = env('FRONTEND_URL');
		$package = Package::find($this->user->selected_package);
		$packageLink = $site_url."/payment/".$this->user->selected_package;
		
        return $this->markdown('emails.radiology-final-report-mail')
                    ->with(['user' => $this->user, 'link' => $packageLink, 'package' => $package]);
    }
}
