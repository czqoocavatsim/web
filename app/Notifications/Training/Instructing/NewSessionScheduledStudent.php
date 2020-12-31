<?php

namespace App\Notifications\Training\Instructing;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Discord\Discord;
use NotificationChannels\Discord\DiscordChannel;
use NotificationChannels\Discord\DiscordMessage;

class NewSessionScheduledStudent extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($session, $type)
    {
        $this->session = $session;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return $notifiable->notificationPreferences->training_notifications == 'email+discord' ? ['mail', DiscordChannel::class] : ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if ($this->type == 'training') {
            return (new MailMessage)
                ->subject("New Training Session Scheduled")
                ->greeting("Hi {$this->session->student->user->fullName('F')},")
                ->line("{$this->session->instructor->user->fullName('FL')} has scheduled a training session with you for {$this->session->scheduled_time->toDayDateTimeString()}.")
                ->line("If you have any questions, please contact your Instructor.")
                ->action('View Session', route('training.portal.sessions.view-training-session', $this->session))
                ->salutation("Gander Oceanic OCA");
        } elseif ($this->type == 'ots') {
            return (new MailMessage)
                ->subject("New OTS Session Scheduled")
                ->greeting("Hi {$this->session->student->user->fullName('F')},")
                ->line("{$this->session->instructor->user->fullName('FL')} has scheduled an OTS session with you for {$this->session->scheduled_time->toDayDateTimeString()}.")
                ->line("If you have any questions, please contact your Assessor.")
                ->action('View Session', route('training.portal.sessions.view-ots-session', $this->session))
                ->salutation("Gander Oceanic OCA");
        }
    }

    /**
     * Get the Discord representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return NotificationChannels\Discord\DiscordMessage
     */
    public function toDiscord($notifiable)
    {
        $message = new DiscordMessage();

        if ($this->type == 'training') {
            $message->embed([
                'title' => 'New Training Session Scheduled',
                'description' => "{$this->session->instructor->user->fullName('FL')} has scheduled a training session with you for {$this->session->scheduled_time->toDayDateTimeString()}. If you have any questions, please contact your Instructor.",
                'color' => 0x80c9,
                "timestamp" => Carbon::now(),
                'footer' => array(
                    'text' => 'You can disable Discord notifications at any time in myCZQO'
                )
            ]);
        } elseif ($this->type == 'ots') {
            $message->embed([
                'title' => 'New OTS Session Scheduled',
                'description' => "{$this->session->instructor->user->fullName('FL')} has scheduled an OTS session with you for {$this->session->scheduled_time->toDayDateTimeString()}. If you have any questions, please contact your Assessor.",
                'color' => 0x80c9,
                "timestamp" => Carbon::now(),
                'footer' => array(
                    'text' => 'You can disable Discord notifications at any time in myCZQO'
                )
            ]);
        }

        return $message;
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
