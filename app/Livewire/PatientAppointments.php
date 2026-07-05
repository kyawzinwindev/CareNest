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

        try {
            $service = app(\App\Services\AppointmentCancellationService::class);
            $service->cancel($appointment);

            session()->flash('message', 'Appointment cancelled successfully.');

        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            abort(403, $e->getMessage());
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage() ?: 'Could not cancel appointment. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.patient-appointments')
            ->layout('components.layouts.app');
    }
}
