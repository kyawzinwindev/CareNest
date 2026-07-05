<?php

namespace App\Services;

use App\Models\Appointment;
use App\Enums\AppointmentStatus;
use App\Enums\TimeSlotStatus;
use App\Notifications\AppointmentCancelledNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class AppointmentCancellationService
{
    /**
     * Cancel an appointment.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \InvalidArgumentException
     */
    public function cancel(Appointment $appointment): void
    {
        // 1. Authorization check
        if (Gate::denies('cancel', $appointment)) {
            throw new \Illuminate\Auth\Access\AuthorizationException('This action is unauthorized.');
        }

        // 2. Validate state (only allow PENDING or CONFIRMED to be cancelled)
        if (!in_array($appointment->status, [AppointmentStatus::PENDING, AppointmentStatus::CONFIRMED])) {
            throw new \InvalidArgumentException('Only pending or confirmed appointments can be cancelled.');
        }

        // 3. Perform cancellation in database transaction
        DB::transaction(function () use ($appointment) {
            // Lock and release slot
            $timeSlot = $appointment->time_slot()->lockForUpdate()->first();
            if ($timeSlot) {
                $timeSlot->update([
                    'status' => TimeSlotStatus::AVAILABLE,
                ]);
            }

            // Update status
            $appointment->update([
                'status' => AppointmentStatus::CANCELLED,
            ]);

            // Notify patient
            $patientUser = $appointment->patient?->user;
            if ($patientUser) {
                $patientUser->notify(new AppointmentCancelledNotification($appointment));
            }
        });
    }
}
