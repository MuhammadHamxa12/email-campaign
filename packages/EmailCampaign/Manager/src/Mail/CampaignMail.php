<?php

namespace EmailCampaign\Manager\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CampaignMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public $subject, public $body, public $variables = [])
    {
    }

    public function build()
    {
        return $this->subject($this->subject)
            ->view('emailcampaign::emails.campaign')
            ->with([
                'body' => $this->body,
                'subject' => $this->subject,
                'variables' => $this->variables
            ]);
    }
}
