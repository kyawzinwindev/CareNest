<?php

namespace App\Observers;

use App\Models\Payment;
use App\Enums\PaymentStatus;
use App\Enums\AppointmentStatus;

class PaymentObserver
{
    /**
     * Handle the Payment "updated" event.
     */
    public function updated(Payment $payment): void
    {
        if ($payment->wasChanged('status') && $payment->status === PaymentStatus::PAID) {
            $appointment = $payment->appointment;
            if ($appointment && $appointment->status !== AppointmentStatus::CONFIRMED) {
                $appointment->update([
                    'status' => AppointmentStatus::CONFIRMED,
                ]);
            }
        }
    }
}
