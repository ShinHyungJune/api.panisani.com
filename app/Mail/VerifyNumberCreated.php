<?php

namespace App\Mail;

use App\Models\AfterService;
use App\Models\Order;
use App\Models\Qna;
use App\Models\VerifyNumber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyNumberCreated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $verifyNumber;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(VerifyNumber $verifyNumber)
    {
        $this->verifyNumber = $verifyNumber;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.verifyNumbers.created')
            ->subject("[".config("app.name")."] 인증번호가 도착하였습니다.")
            ->with(["item" => $this->verifyNumber]);
    }
}
