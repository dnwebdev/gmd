<?php

namespace App\Mail\Ota;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApprovedOtaEmail extends Mailable
{
    use Queueable, SerializesModels;
    private $product;
    private $ota;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($product,$ota)
    {
        //
        $this->product = $product;
        $this->ota = $ota;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Approved OTA Request')
            ->view('mail.ota.approve', ['product' => $this->product,'ota'=>$this->ota]);
    }
}
