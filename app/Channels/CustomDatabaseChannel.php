<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;

class CustomDatabaseChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send(object $notifiable, Notification $notification): void
    {
        if (method_exists($notification, 'toCustomDatabase')) {
            $data = $notification->toCustomDatabase($notifiable);

            $notifiable->notifications()->create([
                'appointment_id' => $data['appointment_id'],
                'title' => $data['title'],
                'message' => $data['message'],
                'is_read' => false,
            ]);
        }
    }
}
