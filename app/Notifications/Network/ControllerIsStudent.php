<?php

namespace App\Notifications\Network;

use App\Models\Network\MonitoredPosition;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ControllerIsStudent extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($log)
    {
        $this->log = $log;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        error_log("sending student");
        return (new MailMessage)
                    ->subject('[ACTIVITYBOT] Student Controller Sign On ('.$this->log->cid.', '.MonitoredPosition::find($this->log->monitored_position_id)->identifier.')')
                    ->line('A student controller has signed onto a position.')
                    ->line('CID: '.$this->log->cid)
                    ->line('Session start: '.$this->log->session_start)
                    ->line('Session end: '.$this->log->session_end)
                    ->line('Position: '. MonitoredPosition::find($this->log->monitored_position_id)->identifier)
                    ->line('Duration: '.$this->log->duration)
                    ->action('View Session (this is broken atm)', route('network.index'));
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
            //
        ];
    }
}