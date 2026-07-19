<?php

namespace App\Services;

use App\Models\Payment;
use App\Enums\PaymentStatus;
use App\Enums\AppointmentStatus;
use App\Enums\TimeSlotStatus;
use App\Notifications\PaymentRejectedNotification;
use Illuminate\Support\Facades\DB;

class PaymentRejectionService
{
    /**
     * Reject a payment.
     *
     * @param  \App\Models\Payment  $payment
     * @return void
     */
    public function reject(Payment $payment): void
    {
        DB::transaction(function () use ($payment) {
            $appointment = $payment->appointment;

            if ($appointment) {
                // 1. Instantly release the specific appointment slot (date/time) to make it Available again
                $timeSlot = $appointment->time_slot()->lockForUpdate()->first();
                if ($timeSlot) {
                    $timeSlot->update([
                        'status' => TimeSlotStatus::AVAILABLE,
                    ]);
                }

                // 2. Update the corresponding appointment status to cancelled
                $appointment->update([
                    'status' => AppointmentStatus::CANCELLED,
                ]);

                // 3. Send in-app database notification to the Patient
                $patientUser = $appointment->patient?->user;
                if ($patientUser) {
                    $patientUser->notify(new PaymentRejectedNotification($appointment));
                }
            }

            // 4. Update the payment status to rejected
            $payment->update([
                'status' => PaymentStatus::REJECTED,
            ]);
        });
    }
}
