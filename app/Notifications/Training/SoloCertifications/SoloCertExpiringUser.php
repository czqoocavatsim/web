<?php

namespace App\Notifications\Training\SoloCertifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Discord\DiscordChannel;
use NotificationChannels\Discord\DiscordMessage;
use Illuminate\Notifications\Messages\MailMessage;

class SoloCertExpiringUser extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($cert)
    {
        $this->cert = $cert;
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
        return $notifiable->notificationPreferences->training_notifications == 'email+discord' ? ['mail', DiscordChannel::class] : ['mail'];
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
            ->subject('Solo Certification Expiring')
            ->greeting('Hi there,')
            ->line('Your solo certification is about to expire.')
            ->line("Expiry: {$this->cert->expires->toFormattedDateString()}")
            ->line("Granted by: {$this->cert->instructor->fullName('FLC')}")
            ->line('Contact your instructor to request an extension or proceed to an OTS assessment.')
            ->line('If you believe this is a mistake or have any questions, please email the Chief Instructor.')
            ->line('*You were sent this email as your training status with Gander Oceanic is about to change.*')
            ->salutation('Gander Oceanic OCA');
    }

    /**
     * Get the Discord representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return NotificationChannels\Discord\DiscordMessage
     */
    public function toDiscord($notifiable)
    {
        $message = new DiscordMessage();

        $message->embed([
            'title'       => 'Solo Certification Expiring',
            'description' => 'Your solo certification is about to expire. Contact your instructor to request an extension or proceed to an OTS assessment. If you believe this is a mistake or have any questions, please email the Chief Instructor.',
            'color'       => 0x80c9,
            'timestamp'   => Carbon::now(),
            'footer'      => [
                'text' => 'You can disable Discord notifications at any time in myCZQO',
            ],
            'fields' => [
                [
                    'name'   => 'Expiry',
                    'value'  => $this->cert->expires->toFormattedDateString(),
                    'inline' => false,
                ],
                [
                    'name'   => 'Granted by',
                    'value'  => $this->cert->instructor->fullName('FLC'),
                    'inline' => false,
                ],
            ],
        ]);

        return $message;
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
