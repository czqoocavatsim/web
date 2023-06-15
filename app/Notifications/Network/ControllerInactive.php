<?php

namespace App\Notifications\Network;

use Illuminate\Bus\Queueable;
use App\Models\Network\MonitoredPosition;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ControllerInactive extends Notification implements ShouldQueue
{
    use Queueable;
    protected $log;
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
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
                    ->subject('[ACTIVITYBOT] Inactive Controller Sign On ('.$this->log->cid.', '.MonitoredPosition::find($this->log->monitored_position_id)->identifier.')')
                    ->line('An inactive controller has signed onto a position.')
                    ->line('CID: '.$this->log->cid)
                    ->line('Session start: '.$this->log->session_start)
                    ->line('Session end: '.$this->log->session_end)
                    ->line('Position: '.MonitoredPosition::find($this->log->monitored_position_id)->identifier)
                    ->line('Duration: '.$this->log->duration)
                    ->action('View Session (this is broken atm)', route('network.index'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
