<?php

namespace App\Notifications;

use App\Models\Appointment;
use App\Channels\CustomDatabaseChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentCancelledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Appointment $appointment)
    {
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', CustomDatabaseChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('CareNest: Appointment Cancelled')
            ->markdown('emails.appointment_cancelled', [
                'appointment' => $this->appointment,
            ]);
    }

    /**
     * Get the custom database representation of the notification.
     */
    public function toCustomDatabase(object $notifiable): array
    {
        $date = \Carbon\Carbon::parse($this->appointment->time_slot->schedule->date)->format('M d, Y');
        $time = $this->appointment->time_slot->start_time;

        return [
            'appointment_id' => $this->appointment->id,
            'title' => 'Appointment Cancelled',
            'message' => sprintf(
                'Your appointment for %s with Dr. %s on %s at %s has been cancelled. If you have any questions,please contact our clinic support immediately',
                $this->appointment->service->name,
                $this->appointment->doctor->user->name,
                $date,
                $time
            ),
        ];
    }
}
