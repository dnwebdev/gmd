<?php

namespace App\Notifications\Update;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UpdateNewsNotification extends Notification
{
    use Queueable;
    private $external_id;
    private $content;
    private $title;
    private $judul;
    private $kontent;
    private $type;
    /**
     * @var null
     */
    private $date;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($external_id,$type,$title,$content,$judul,$kontent,$date=null)
    {
        //
        $this->external_id = $external_id;
        $this->content = $content;
        $this->title = $title;
        $this->judul = $judul;
        $this->kontent = $kontent;
        $this->type = $type;
        $this->date = $date;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'type'=>$this->type,
            'external_id'=>$this->external_id,
            'title'=>$this->title,
            'content'=>$this->content,
            'title_indonesia'=>$this->judul,
            'content_indonesia'=>$this->kontent,
            'date'=>$this->date==null?Carbon::now()->format('d/m/Y'):$this->date
        ];
    }
}
