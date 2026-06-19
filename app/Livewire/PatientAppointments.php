<?php

namespace App\Livewire;

use App\Enums\AppointmentStatus;
use App\Enums\Role;
use App\Enums\TimeSlotStatus;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PatientAppointments extends Component
{
    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role !== Role::PATIENT) {
            return redirect('/');
        }
    }

    public function getAppointmentsProperty()
    {
        $patient = Auth::user()->patient;
        if (!$patient) {
            return collect();
        }

        return Appointment::where('patient_id', $patient->id)
            ->with(['doctor.user', 'service', 'time_slot.schedule'])
            ->orderBy('id', 'desc')
            ->get();
    }

    public function cancelAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);

        // Ensure ownership
        if ($appointment->patient_id !== Auth::user()->patient?->id) {
            abort(403);
        }

        // Only allow cancellation if pending
        if ($appointment->status !== AppointmentStatus::PENDING) {
            session()->flash('error', 'Only pending appointments can be cancelled.');
            return;
        }

        try {
            DB::transaction(function () use ($appointment) {
                // Lock slot
                $timeSlot = $appointment->time_slot()->lockForUpdate()->first();

                // Update appointment status
                $appointment->update([
                    'status' => AppointmentStatus::CANCELLED
                ]);

                // Free up the time slot
                if ($timeSlot) {
                    $timeSlot->update([
                        'status' => TimeSlotStatus::AVAILABLE
                    ]);
                }
            });

            session()->flash('message', 'Appointment cancelled successfully.');

        } catch (\Exception $e) {
            session()->flash('error', 'Could not cancel appointment. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.patient-appointments')
            ->layout('components.layouts.app');
    }
}
