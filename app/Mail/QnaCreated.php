<?php

namespace App\Mail;

use App\Models\AfterService;
use App\Models\Order;
use App\Models\Qna;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QnaCreated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $qna;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Qna $qna)
    {
        $this->qna = $qna;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.qnas.created')
            ->subject("[".config("app.name")."] 세입자 문의가 접수되었습니다.")
            ->with(["item" => $this->qna]);
    }
}
