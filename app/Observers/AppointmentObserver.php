<?php

namespace App\Observers;

use App\Models\Appointment;
use App\Enums\AppointmentStatus;
use App\Enums\TimeSlotStatus;
use Illuminate\Support\Facades\DB;

class AppointmentObserver
{
    /**
     * Handle the Appointment "updated" event.
     */
    public function updated(Appointment $appointment): void
    {
        if ($appointment->wasChanged('status')) {
            if ($appointment->status === AppointmentStatus::CANCELLED) {
                DB::transaction(function () use ($appointment) {
                    $timeSlot = $appointment->time_slot()->lockForUpdate()->first();
                    if ($timeSlot) {
                        $timeSlot->update([
                            'status' => TimeSlotStatus::AVAILABLE,
                        ]);
                    }
                });
            } elseif ($appointment->status === AppointmentStatus::CONFIRMED) {
                DB::transaction(function () use ($appointment) {
                    $timeSlot = $appointment->time_slot()->lockForUpdate()->first();
                    if ($timeSlot) {
                        $timeSlot->update([
                            'status' => TimeSlotStatus::BOOKED,
                        ]);
                    }
                });
            }
        }
    }
}
