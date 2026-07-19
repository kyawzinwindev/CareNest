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
            'status' => AppointmentStatus::PENDING,
        ]);

        // 6. Create Payment (Pending)
        $payment = Payment::create([
            'appointment_id' => $appointment->id,
            'amount' => 1500,
            'method' => PaymentMethod::CARD,
            'status' => PaymentStatus::PENDING,
            'screenshot' => 'payments/dummy.png',
            'paid_at' => now(),
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
            'status' => AppointmentStatus::CONFIRMED,
        ]);

        $this->assertEquals(TimeSlotStatus::BOOKED, $timeSlot->fresh()->status);

        // 6. Update Appointment status to CANCELLED
        $appointment->update(['status' => AppointmentStatus::CANCELLED]);

        // 7. Assert TimeSlot is reverted to AVAILABLE
        $this->assertEquals(TimeSlotStatus::AVAILABLE, $timeSlot->fresh()->status);
    }

    public function test_appointment_deletion_restricted_to_root_only()
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
            'status' => AppointmentStatus::CONFIRMED,
        ]);

        // Assert ROOT cannot delete when status is CONFIRMED
        $this->actingAs($rootUser);
        $this->assertFalse($rootUser->can('delete', $appointment));
        $this->assertFalse($rootUser->can('forceDelete', $appointment));

        // Update status to FINISHED
        $appointment->update(['status' => AppointmentStatus::FINISHED]);

        // Assert ROOT can delete when status is FINISHED
        $this->assertTrue($rootUser->can('delete', $appointment));
        $this->assertTrue($rootUser->can('forceDelete', $appointment));

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

    public function test_payment_deletion_restricted_to_root_only()
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
            'status' => AppointmentStatus::PENDING,
        ]);

        $payment = Payment::create([
            'appointment_id' => $appointment->id,
            'amount' => 1500,
            'method' => PaymentMethod::CARD,
            'status' => PaymentStatus::PENDING,
        ]);

        // Assert ROOT can delete
        $this->actingAs($rootUser);
        $this->assertTrue($rootUser->can('delete', $payment));
        $this->assertTrue($rootUser->can('forceDelete', $payment));

        // Assert ADMIN cannot delete
        $this->actingAs($adminUser);
        $this->assertFalse($adminUser->can('delete', $payment));
        $this->assertFalse($adminUser->can('forceDelete', $payment));
    }

    public function test_payment_rejection_workflow()
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
            'status' => AppointmentStatus::PENDING,
        ]);

        // 6. Create Payment (Pending)
        $payment = Payment::create([
            'appointment_id' => $appointment->id,
            'amount' => 1500,
            'method' => PaymentMethod::CARD,
            'status' => PaymentStatus::PENDING,
        ]);

        // 7. Perform rejection via the service
        app(\App\Services\PaymentRejectionService::class)->reject($payment);

        // 8. Assert payment status is rejected and appointment status is cancelled
        $this->assertEquals(PaymentStatus::REJECTED, $payment->fresh()->status);
        $this->assertEquals(AppointmentStatus::CANCELLED, $appointment->fresh()->status);

        // 9. Assert time slot is available
        $this->assertEquals(TimeSlotStatus::AVAILABLE, $timeSlot->fresh()->status);

        // 10. Assert Patient received database notification stating payment was rejected
        $notification = $patientUser->notifications()->first();
        $this->assertNotNull($notification);
        $this->assertEquals('Payment Rejected', $notification->title);
        $this->assertStringContainsString('rejected', $notification->message);
        $this->assertStringContainsString('re-book', $notification->message);
    }

    public function test_confirming_appointment_without_paid_payment_throws_validation_exception()
    {
        $patientUser = User::factory()->create(['role' => Role::PATIENT]);
        $patient = Patient::create(['user_id' => $patientUser->id, 'dob' => '1995-05-15', 'weight' => 70, 'height' => 175]);

        $doctorUser = User::factory()->create(['role' => Role::DOCTOR]);
        $doctor = Doctor::create(['user_id' => $doctorUser->id, 'specialization' => \App\Enums\Specialization::CARDIOLOGY]);

        $service = Service::create([
            'name' => 'Cardio Consultation',
            'description' => 'Test cardiology service description',
            'price' => 1500,
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
            'status' => AppointmentStatus::PENDING,
        ]);

        $payment = Payment::create([
            'appointment_id' => $appointment->id,
            'amount' => 1500,
            'method' => PaymentMethod::CARD,
            'status' => PaymentStatus::PENDING,
        ]);

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->expectExceptionMessage('Appointment cannot be confirmed because payment status is not Paid.');

        $appointment->update(['status' => AppointmentStatus::CONFIRMED]);
    }

    public function test_confirming_appointment_with_paid_payment_creates_notification_and_sends_email()
    {
        \Illuminate\Support\Facades\Mail::fake();

        $patientUser = User::factory()->create(['role' => Role::PATIENT]);
        $patient = Patient::create(['user_id' => $patientUser->id, 'dob' => '1995-05-15', 'weight' => 70, 'height' => 175]);

        $doctorUser = User::factory()->create(['role' => Role::DOCTOR]);
        $doctor = Doctor::create(['user_id' => $doctorUser->id, 'specialization' => \App\Enums\Specialization::CARDIOLOGY]);

        $service = Service::create([
            'name' => 'Cardio Consultation',
            'description' => 'Test cardiology service description',
            'price' => 1500,
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
            'status' => AppointmentStatus::PENDING,
        ]);

        $payment = Payment::create([
            'appointment_id' => $appointment->id,
            'amount' => 1500,
            'method' => PaymentMethod::CARD,
            'status' => PaymentStatus::PAID,
        ]);

        $appointment->update(['status' => AppointmentStatus::CONFIRMED]);

        $this->assertEquals(AppointmentStatus::CONFIRMED, $appointment->fresh()->status);

        // Assert notification created
        $this->assertDatabaseHas('notifications', [
            'user_id' => $patientUser->id,
            'appointment_id' => $appointment->id,
            'title' => 'Appointment Confirmed',
        ]);

        // Assert email queued
        \Illuminate\Support\Facades\Mail::assertQueued(\App\Mail\AppointmentConfirmedMail::class, function ($mail) use ($appointment) {
            return $mail->appointment->id === $appointment->id;
        });
    }

    public function test_appointment_cancellation_service_authorizes_correctly()
    {
        // 1. Setup Patient 1 and Patient 2
        $patientUser1 = User::factory()->create(['role' => Role::PATIENT]);
        $patient1 = Patient::create(['user_id' => $patientUser1->id, 'dob' => '1995-05-15', 'weight' => 70, 'height' => 175]);

        $patientUser2 = User::factory()->create(['role' => Role::PATIENT]);
        $patient2 = Patient::create(['user_id' => $patientUser2->id, 'dob' => '1995-05-15', 'weight' => 70, 'height' => 175]);

        // 2. Setup Doctor 1 and Doctor 2
        $doctorUser1 = User::factory()->create(['role' => Role::DOCTOR]);
        $doctor1 = Doctor::create(['user_id' => $doctorUser1->id, 'specialization' => \App\Enums\Specialization::CARDIOLOGY]);

        $doctorUser2 = User::factory()->create(['role' => Role::DOCTOR]);
        $doctor2 = Doctor::create(['user_id' => $doctorUser2->id, 'specialization' => \App\Enums\Specialization::CARDIOLOGY]);

        // 3. Setup Admin
        $adminUser = User::factory()->create(['role' => Role::ADMIN]);

        // 4. Setup Service
        $service = Service::create([
            'name' => 'Cardio Consultation',
            'description' => 'Test cardiology service description',
            'price' => 1500,
            'specialization' => \App\Enums\Specialization::CARDIOLOGY
        ]);

        // 5. Setup Schedule and TimeSlot for Doctor 1
        $schedule = Schedule::create([
            'doctor_id' => $doctor1->id,
            'date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
            'slot_duration_minutes' => 60
        ]);
        $timeSlot = TimeSlot::create(['schedule_id' => $schedule->id, 'start_time' => '10:00:00', 'end_time' => '11:00:00', 'status' => TimeSlotStatus::BOOKED]);

        // 6. Create Appointment for Patient 1 with Doctor 1
        $appointment = Appointment::create([
            'patient_id' => $patient1->id,
            'doctor_id' => $doctor1->id,
            'service_id' => $service->id,
            'time_slot_id' => $timeSlot->id,
            'status' => AppointmentStatus::PENDING,
        ]);

        $serviceInstance = app(\App\Services\AppointmentCancellationService::class);

        // A. Patient 2 cannot cancel Patient 1's appointment
        $this->actingAs($patientUser2);
        try {
            $serviceInstance->cancel($appointment);
            $this->fail('Patient 2 was able to cancel Patient 1\'s appointment.');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            $this->assertTrue(true);
        }

        // B. Doctor 2 cannot cancel Doctor 1's appointment
        $this->actingAs($doctorUser2);
        try {
            $serviceInstance->cancel($appointment);
            $this->fail('Doctor 2 was able to cancel Doctor 1\'s appointment.');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            $this->assertTrue(true);
        }

        // C. Patient 1 can cancel their own appointment
        $this->actingAs($patientUser1);
        $serviceInstance->cancel($appointment);
        $this->assertEquals(AppointmentStatus::CANCELLED, $appointment->fresh()->status);

        // Reset status for further tests
        $appointment->update(['status' => AppointmentStatus::PENDING]);

        // D. Doctor 1 can cancel their own appointment
        $this->actingAs($doctorUser1);
        $serviceInstance->cancel($appointment);
        $this->assertEquals(AppointmentStatus::CANCELLED, $appointment->fresh()->status);

        // Reset status for further tests
        $appointment->update(['status' => AppointmentStatus::PENDING]);

        // E. Admin can cancel any appointment
        $this->actingAs($adminUser);
        $serviceInstance->cancel($appointment);
        $this->assertEquals(AppointmentStatus::CANCELLED, $appointment->fresh()->status);
    }

    public function test_appointment_cancellation_service_performs_transactional_updates_and_sends_notifications()
    {
        \Illuminate\Support\Facades\Notification::fake();

        // 1. Create Patient
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
            'status' => AppointmentStatus::CONFIRMED,
        ]);

        // Act as Patient
        $this->actingAs($patientUser);

        // Run cancellation service
        $serviceInstance = app(\App\Services\AppointmentCancellationService::class);
        $serviceInstance->cancel($appointment);

        // Assert appointment is CANCELLED
        $this->assertEquals(AppointmentStatus::CANCELLED, $appointment->fresh()->status);

        // Assert TimeSlot is AVAILABLE
        $this->assertEquals(TimeSlotStatus::AVAILABLE, $timeSlot->fresh()->status);

        // Assert Notification was sent
        \Illuminate\Support\Facades\Notification::assertSentTo(
            $patientUser,
            \App\Notifications\AppointmentCancelledNotification::class,
            function ($notification) use ($appointment) {
                return $notification->appointment->id === $appointment->id;
            }
        );
    }

    public function test_deleting_appointment_cascades_to_payment()
    {
        // 1. Create Patient
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

        // 5. Create Appointment
        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'service_id' => $service->id,
            'time_slot_id' => $timeSlot->id,
            'status' => AppointmentStatus::CONFIRMED,
        ]);

        // 6. Create Payment
        $payment = Payment::create([
            'appointment_id' => $appointment->id,
            'amount' => 1500,
            'method' => PaymentMethod::CARD,
            'status' => PaymentStatus::PAID,
            'screenshot' => 'payments/dummy.png',
            'paid_at' => now(),
        ]);

        $this->assertDatabaseHas('appointments', ['id' => $appointment->id]);
        $this->assertDatabaseHas('payments', ['id' => $payment->id]);

        // Delete Appointment
        $appointment->delete();

        // Assert both are deleted from database
        $this->assertDatabaseMissing('appointments', ['id' => $appointment->id]);
        $this->assertDatabaseMissing('payments', ['id' => $payment->id]);
    }
}
