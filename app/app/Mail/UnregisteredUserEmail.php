<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UnregisteredUserEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $toEmail;
    public $fromEmail;
    public $recoveryLink;

    /**
     * Create a new message instance.
     *
     * @param $toEmail
     * @param $recoveryLink
     * @param null $fromEmail
     */
    public function __construct($toEmail,  $fromEmail = null)
    {
        $this->toEmail = $toEmail;
        $this->fromEmail = $fromEmail;
        $this->fromEmail = $fromEmail ? $fromEmail : 'noreply@accesslocator.com';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->toEmail)
            ->from($this->fromEmail)
            ->subject('Password Recovery Request AccessLocator')
            ->view('mails.password_recovery_unmatched_email');
    }
}
