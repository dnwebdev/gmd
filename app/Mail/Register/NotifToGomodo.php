<?php

namespace App\Mail\Register;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifToGomodo extends Mailable
{
    use Queueable, SerializesModels;
    private $name;
    private $provider;
    private $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($provider)
    {
        $this->provider = $provider;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = [
            'name'=>$this->name,
            'provider'=>$this->provider
        ];
        $ccs = ['heri.karisma@gomodo.tech', 'support@mygomodo.com', 'fichainggit@gomodo.tech', 'lw@gomodo.tech'];
        return $this->from('no-reply@'.env('APP_URL'))
            ->subject('New Provider')
            ->cc($ccs)
            ->view('mail.register.notifnewregister',$data);
    }
}
