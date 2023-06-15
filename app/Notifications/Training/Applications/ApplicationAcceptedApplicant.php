<?php

namespace App\Notifications\Training\Applications;

use Illuminate\Bus\Queueable;
use App\Models\Training\Application;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Discord\DiscordChannel;
use NotificationChannels\Discord\DiscordMessage;
use Illuminate\Notifications\Messages\MailMessage;

class ApplicationAcceptedApplicant extends Notification implements ShouldQueue
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
        return (new MailMessage())
        ->subject('Your Application Has Been Accepted!')
        ->greeting("Hello {$this->application->user->fullName('FLC')},")
        ->line('Congratulations! Your application for Gander Oceanic has been accepted. You can now take the next steps to achieving your oceanic certification.')
        ->line('**How to get started**')
        ->line('To begin your training, head to the Training Portal in myCZQO and submit your availability for the next two weeks. This will allow us to assign you an Instructor who is best suited to your time zone.')
        ->action('Submit Your Availability', route('training.portal.index'))
        ->salutation('Kind regards, Gander Oceanic OCA')
        ->line('You received this email as there is an important update to your status with Gander Oceanic.');
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
            'title'       => 'Your application for Gander Oceanic has been accepted!',
            'url'         => route('training.applications.show', $this->application->reference_id),
            'description' => 'Congratulations! Please check your email inbox for further instructions.',
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
