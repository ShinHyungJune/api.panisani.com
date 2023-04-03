<?php

namespace App\Mail;

use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetCreated extends Mailable
{
    use Queueable, SerializesModels;

    protected $receiver;

    protected $passwordReset;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $receiver, PasswordReset $passwordReset)
    {
        $this->receiver = $receiver;

        $this->passwordReset = $passwordReset;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.passwordResets.created')
            ->subject("[".config("app.name")."] ".__("socialLogin.passwordReset")["send"])
            ->with(["receiver" => $this->receiver, "passwordReset" => $this->passwordReset]);
    }
}
