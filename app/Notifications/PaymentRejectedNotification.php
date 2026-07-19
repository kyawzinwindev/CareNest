<?php

namespace App\Notifications;

use App\Models\Appointment;
use App\Channels\CustomDatabaseChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PaymentRejectedNotification extends Notification implements ShouldQueue
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
        return [CustomDatabaseChannel::class];
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
            'title' => 'Payment Rejected',
            'message' => sprintf(
                'Your payment for the appointment with Dr. %s on %s at %s was rejected. You need to re-book your appointment slot.',
                $this->appointment->doctor->user->name,
                $date,
                $time
            ),
        ];
    }
}
