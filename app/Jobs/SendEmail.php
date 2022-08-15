<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;
use PDF;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    var $to;
    var $subject;
    var $template;
    var $pdf;
    var $item;
    var $image;
    /**
     * @var array
     */
    private $cc;

    public function __construct($subject, $to, $template, $image = [], $pdf = null, $item = [], $cc = [])
    {
        $this->subject = $subject;
        $this->to = $to;
        $this->template = $template;
        $this->cc = $cc;
        $this->image = $image;
        if (!empty($pdf)) {
            $this->pdf = $pdf;
            $this->item = $item;
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $template = $this->template;
        $subject = $this->subject;
        $to = $this->to;
        $pdf = null;
        $item = null;
        $attach = null;
        $image =null;
        if (!empty($this->image)){
            $image = $this->image;
        }
        if (!empty($this->pdf)) {
            $pdf = $this->pdf;
            $item = $this->item;
            $attach = PDF::setPaper('A4')->loadView($pdf, $item);
        }
        if (!empty($to)) {
            Mail::send([], [], function ($message) use ($subject, $to, $template, $attach, $image) {
                $message->to($to);
                foreach ($this->cc as $cc) {
                    $message->cc($cc);
                }
                $message->subject($subject)
                    ->setBody($template, 'text/html');
                if (!empty($image)){
                    foreach ($image as $data) {
                        $message->attach(storage_path('app/public' . str_replace('storage/', '', $data)));
                    }
                }
                if (!empty($attach)){
                    $message->attachData($attach->output(), $subject . ".pdf");
                }
            });
        }
    }
}
