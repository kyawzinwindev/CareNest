<?php

namespace App\Observers;

use App\Models\Appointment;
use App\Enums\AppointmentStatus;
use App\Enums\TimeSlotStatus;
use Illuminate\Support\Facades\DB;

class AppointmentObserver
{
    /**
     * Handle the Appointment "updating" event.
     */
    public function updating(Appointment $appointment): void
    {
        if ($appointment->isDirty('status') && $appointment->status === AppointmentStatus::CONFIRMED) {
            $payment = $appointment->payment()->first();
            if (!$payment || $payment->status !== \App\Enums\PaymentStatus::PAID) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'status' => 'Appointment cannot be confirmed because payment status is not Paid.',
                ]);
            }
        }
    }

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

                    $appointment->patient->user->notifications()->create([
                        'appointment_id' => $appointment->id,
                        'title' => 'Appointment Confirmed',
                        'message' => sprintf(
                            'Your appointment for %s with Dr. %s on %s is confirmed.',
                            $appointment->service->name,
                            $appointment->doctor->user->name,
                            \Carbon\Carbon::parse($appointment->time_slot->schedule->date)->format('M d, Y') . ' at ' . $appointment->time_slot->start_time
                        ),
                        'is_read' => false,
                    ]);

                    \Illuminate\Support\Facades\Mail::to($appointment->patient->user->email)
                        ->queue(new \App\Mail\AppointmentConfirmedMail($appointment));
                });
            }
        }
    }
}
