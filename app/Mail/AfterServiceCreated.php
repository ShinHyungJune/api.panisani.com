<?php

namespace App\Mail;

use App\Models\AfterService;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AfterServiceCreated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $afterService;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(AfterService $afterService)
    {
        $this->afterService = $afterService;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.afterServices.created')
            ->subject("[".config("app.name")."] 세입자 AS가 접수되었습니다.")
            ->with(["item" => $this->afterService]);
    }
}
