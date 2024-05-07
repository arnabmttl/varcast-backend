<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user  = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */

    public function build()
    {
        $subject = 'Reset Password Notification';

        return $this->view('mail.admin_password_reset')
            ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->subject($subject)
            ->with(['user' => $this->user]);
    }
}
