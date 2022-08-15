<?php

namespace App\Mail\Ota;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RejectedOtaEmail extends Mailable
{
    use Queueable, SerializesModels;
    private $product;
    private $ota;
    private $reason;
    private $reason_en;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($product,$ota,$reason,$reason_en)
    {
        //
        $this->product = $product;
        $this->ota = $ota;
        $this->reason = $reason;
        $this->reason_en = $reason_en;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Rejected OTA Request')
            ->view('mail.ota.decline', ['product' => $this->product,'ota'=>$this->ota,'reason'=>$this->reason,'reason_en'=>$this->reason_en]);
    }
}
