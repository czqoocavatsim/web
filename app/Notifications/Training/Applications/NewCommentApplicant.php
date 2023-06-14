<?php

namespace App\Notifications\Training\Applications;

use Illuminate\Bus\Queueable;
use App\Models\Training\Application;
use Illuminate\Notifications\Notification;
use App\Models\Training\ApplicationComment;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Discord\DiscordChannel;
use NotificationChannels\Discord\DiscordMessage;
use Illuminate\Notifications\Messages\MailMessage;

class NewCommentApplicant extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Application $application, ApplicationComment $comment)
    {
        $this->application = $application;
        $this->comment = $comment;
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
            'emails.training.applications.newcommentapplicant',
            ['application' => $this->application, 'comment' => $this->comment]
        )->subject('#'.$this->application->reference_id.' - New Comment From Staff Member');
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
            'title'       => 'New comment on your application for Gander Oceanic',
            'url'         => route('training.applications.show', $this->application->reference_id),
            'description' => "{$this->comment->user->fullName('FLC')} has left a comment on your application for Gander Oceanic. They may be asking for more information about your application or notifying you of your application status.",
            'color'       => 0x80c9,
            'author'      => [
                'name' => $this->comment->user->fullName('FLC'),
            ],
            'fields' => [
                [
                    'name'   => 'Comment',
                    'value'  => $this->comment->content,
                    'inline' => false,
                ],
            ],
            'timestamp' => date('Y-m-d H:i:s'),
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
