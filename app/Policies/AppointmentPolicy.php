<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($ability === 'delete' || $ability === 'forceDelete') {
            return false;
        }

        if ($user->role === Role::ROOT) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === Role::ADMIN || $user->role === Role::DOCTOR;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Appointment $appointment): bool
    {
        if ($user->role === Role::ADMIN) {
            return true;
        }

        if ($user->role === Role::DOCTOR) {
            return $user->doctor && $user->doctor->id === $appointment->doctor_id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === Role::ADMIN;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Appointment $appointment): bool
    {
        if ($user->role === Role::ADMIN) {
            return true;
        }

        if ($user->role === Role::DOCTOR) {
            return $user->doctor && $user->doctor->id === $appointment->doctor_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Appointment $appointment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Appointment $appointment): bool
    {
        if ($user->role === Role::ADMIN) {
            return true;
        }

        if ($user->role === Role::DOCTOR) {
            return $user->doctor && $user->doctor->id === $appointment->doctor_id;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Appointment $appointment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can cancel the appointment.
     */
    public function cancel(User $user, Appointment $appointment): bool
    {
        if ($user->role === Role::ADMIN) {
            return true;
        }

        if ($user->role === Role::DOCTOR) {
            return $user->doctor && $user->doctor->id === $appointment->doctor_id;
        }

        if ($user->role === Role::PATIENT) {
            return $user->patient && $user->patient->id === $appointment->patient_id;
        }

        return false;
    }
}
