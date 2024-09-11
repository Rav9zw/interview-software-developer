<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ParkingSessionEnding extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $session;

    public function __construct($session)
    {
        $this->session = $session;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your parking session is ending soon')
            ->line('Your parking session for vehicle ' . $this->session->vehicle->type . ' is ending in 15 minutes.')
            ->line('Parking spot: ' . $this->session->parkingSpot->spot_number)
            ->line('End time: ' . $this->session->end_time)
            ->line('Thank you for using our service!');
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
