<?php

namespace App\Notifications;

use App\Mail\MeetingInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MeetingCreated extends Notification
{
    use Queueable;

    protected $meeting;
    protected $user;
    /**
     * Create a new notification instance.
     */
    public function __construct($meeting,$user)
    {
        $this->meeting = $meeting;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the data array that represents the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable): array
    {
        return [
            'meeting_id' => $this->meeting->id,
            'meeting_title' => $this->meeting->title,
            'start_time' => $this->meeting->start_time,
            'user' => $this->meeting->user->name,
            'message' => $this->meeting->user->name.'has invited you to a meeting.',
        ];
    }

    /**
     * Send the email using the Mailable.
     *
     * @param mixed $notifiable
     * @return void
     */
    public function toMail($notifiable)
    {
        return (new MeetingInvitation($this->user,$this->meeting))
            ->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
