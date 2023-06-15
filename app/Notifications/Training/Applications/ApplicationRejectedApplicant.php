<?php

namespace App\Notifications\Training\Applications;

use Illuminate\Bus\Queueable;
use App\Models\Training\Application;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Discord\DiscordChannel;
use NotificationChannels\Discord\DiscordMessage;
use Illuminate\Notifications\Messages\MailMessage;

class ApplicationRejectedApplicant extends Notification implements ShouldQueue
{
    use Queueable;
    protected $application;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
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
        return $notifiable->preferences->enable_discord_notifications ? ['mail', DiscordChannel::class] : ['mail'];
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
        return (new MailMessage())->view(
            'emails.training.applications.applicationrejectedapplicant',
            ['application' => $this->application]
        )->subject('#'.$this->application->reference_id.' - Application Rejected');
    }

    /**
     * @param $notifiable
     *
     * @return DiscordMessage
     */
    public function toDiscord($notifiable)
    {
        $message = new DiscordMessage();

        $message->embed([
            'title'       => 'Your application for Gander Oceanic has been rejected',
            'url'         => route('training.applications.show', $this->application->reference_id),
            'description' => 'Your application for Gander Oceanic has been rejected. This may be because you do not meet the requirements as per our General Policy. You can view the exact reason for rejection by clicking the title of this embed.',
            'color'       => 0x80c9,
            'timestamp'   => date('Y-m-d H:i:s'),
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
