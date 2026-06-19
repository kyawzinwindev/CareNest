<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Service;
use App\Models\Schedule;
use App\Models\TimeSlot;
use App\Models\Appointment;
use App\Models\Payment;
use App\Enums\Role;
use App\Enums\PaymentStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentType;
use App\Enums\AppointmentStatus;
use App\Enums\TimeSlotStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CareNestBusinessLogicTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_to_paid_transition_automatically_confirms_appointment()
    {
        // 1. Create a Patient User & Profile
        $patientUser = User::factory()->create(['role' => Role::PATIENT]);
        $patient = Patient::create(['user_id' => $patientUser->id, 'dob' => '1995-05-15', 'weight' => 70, 'height' => 175]);

        // 2. Create Doctor
        $doctorUser = User::factory()->create(['role' => Role::DOCTOR]);
        $doctor = Doctor::create(['user_id' => $doctorUser->id, 'specialization' => \App\Enums\Specialization::CARDIOLOGY]);

        // 3. Create Service
        $service = Service::create([
            'name' => 'Cardio Consultation',
            'description' => 'Test cardiology service description',
            'price' => 1500,
            'required_prepayment' => true,
            'specialization' => \App\Enums\Specialization::CARDIOLOGY
        ]);

        // 4. Create Schedule and TimeSlot
        $schedule = Schedule::create([
            'doctor_id' => $doctor->id,
            'date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
            'slot_duration_minutes' => 60
        ]);
        $timeSlot = TimeSlot::create(['schedule_id' => $schedule->id, 'start_time' => '10:00:00', 'end_time' => '11:00:00', 'status' => TimeSlotStatus::BOOKED]);

        // 5. Create Appointment (Pending)
        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'service_id' => $service->id,
            'time_slot_id' => $timeSlot->id,
            'payment_type' => PaymentType::ONLINE,
            'status' => AppointmentStatus::PENDING,
        ]);

        // 6. Create Payment (Pending)
        $payment = Payment::create([
            'appointment_id' => $appointment->id,
            'amount' => 1500,
            'method' => PaymentMethod::CARD,
            'status' => PaymentStatus::PENDING,
            'screenshot' => 'payments/dummy.png',
            'paid_at' => now()->toTimeString(),
        ]);

        $this->assertEquals(AppointmentStatus::PENDING, $appointment->fresh()->status);

        // 7. Update Payment to PAID
        $payment->update(['status' => PaymentStatus::PAID]);

        // 8. Assert Appointment is automatically CONFIRMED
        $this->assertEquals(AppointmentStatus::CONFIRMED, $appointment->fresh()->status);
    }

    public function test_appointment_cancelled_transitions_reverts_timeslot_to_available()
    {
        // 1. Create a Patient User & Profile
        $patientUser = User::factory()->create(['role' => Role::PATIENT]);
        $patient = Patient::create(['user_id' => $patientUser->id, 'dob' => '1995-05-15', 'weight' => 70, 'height' => 175]);

        // 2. Create Doctor
        $doctorUser = User::factory()->create(['role' => Role::DOCTOR]);
        $doctor = Doctor::create(['user_id' => $doctorUser->id, 'specialization' => \App\Enums\Specialization::CARDIOLOGY]);

        // 3. Create Service
        $service = Service::create([
            'name' => 'Cardio Consultation',
            'description' => 'Test cardiology service description',
            'price' => 1500,
            'required_prepayment' => true,
            'specialization' => \App\Enums\Specialization::CARDIOLOGY
        ]);

        // 4. Create Schedule and TimeSlot
        $schedule = Schedule::create([
            'doctor_id' => $doctor->id,
            'date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
            'slot_duration_minutes' => 60
        ]);
        $timeSlot = TimeSlot::create(['schedule_id' => $schedule->id, 'start_time' => '10:00:00', 'end_time' => '11:00:00', 'status' => TimeSlotStatus::BOOKED]);

        // 5. Create Appointment (Confirmed)
        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'service_id' => $service->id,
            'time_slot_id' => $timeSlot->id,
            'payment_type' => PaymentType::ONLINE,
            'status' => AppointmentStatus::CONFIRMED,
        ]);

        $this->assertEquals(TimeSlotStatus::BOOKED, $timeSlot->fresh()->status);

        // 6. Update Appointment status to CANCELLED
        $appointment->update(['status' => AppointmentStatus::CANCELLED]);

        // 7. Assert TimeSlot is reverted to AVAILABLE
        $this->assertEquals(TimeSlotStatus::AVAILABLE, $timeSlot->fresh()->status);
    }

    public function test_appointment_deletion_denied_for_all_roles()
    {
        $rootUser = User::factory()->create(['role' => Role::ROOT]);
        $adminUser = User::factory()->create(['role' => Role::ADMIN]);
        $patientUser = User::factory()->create(['role' => Role::PATIENT]);
        $patient = Patient::create(['user_id' => $patientUser->id, 'dob' => '1995-05-15', 'weight' => 70, 'height' => 175]);

        $doctorUser = User::factory()->create(['role' => Role::DOCTOR]);
        $doctor = Doctor::create(['user_id' => $doctorUser->id, 'specialization' => \App\Enums\Specialization::CARDIOLOGY]);

        $service = Service::create([
            'name' => 'Cardio Consultation',
            'description' => 'Test cardiology service description',
            'price' => 1500,
            'required_prepayment' => true,
            'specialization' => \App\Enums\Specialization::CARDIOLOGY
        ]);

        $schedule = Schedule::create([
            'doctor_id' => $doctor->id,
            'date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
            'slot_duration_minutes' => 60
        ]);
        $timeSlot = TimeSlot::create(['schedule_id' => $schedule->id, 'start_time' => '10:00:00', 'end_time' => '11:00:00', 'status' => TimeSlotStatus::BOOKED]);

        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'service_id' => $service->id,
            'time_slot_id' => $timeSlot->id,
            'payment_type' => PaymentType::ONLINE,
            'status' => AppointmentStatus::CONFIRMED,
        ]);

        // Assert ROOT cannot delete
        $this->actingAs($rootUser);
        $this->assertFalse($rootUser->can('delete', $appointment));
        $this->assertFalse($rootUser->can('forceDelete', $appointment));

        // Assert ADMIN cannot delete
        $this->actingAs($adminUser);
        $this->assertFalse($adminUser->can('delete', $appointment));
        $this->assertFalse($adminUser->can('forceDelete', $appointment));

        // Assert DOCTOR cannot delete
        $this->actingAs($doctorUser);
        $this->assertFalse($doctorUser->can('delete', $appointment));
        $this->assertFalse($doctorUser->can('forceDelete', $appointment));

        // Assert PATIENT cannot delete
        $this->actingAs($patientUser);
        $this->assertFalse($patientUser->can('delete', $appointment));
        $this->assertFalse($patientUser->can('forceDelete', $appointment));
    }
}
