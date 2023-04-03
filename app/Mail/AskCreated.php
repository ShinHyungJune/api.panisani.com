<?php

namespace App\Mail;

use App\Models\AfterService;
use App\Models\Ask;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AskCreated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $ask;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Ask $ask)
    {
        $this->ask = $ask;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.asks.created')
            ->subject("[".config("app.name")."] 빌라위탁 문의가 접수되었습니다.")
            ->with(["item" => $this->ask]);
    }
}
