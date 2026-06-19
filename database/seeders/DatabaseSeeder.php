<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Schedule;
use App\Models\TimeSlot;
use App\Models\Appointment;
use App\Models\Payment;
use App\Enums\Role;
use App\Enums\Specialization;
use App\Enums\TimeSlotStatus;
use App\Enums\AppointmentStatus;
use App\Enums\PaymentType;
use App\Enums\PaymentStatus;
use App\Enums\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Root User
        User::factory()->create([
            'name' => 'Root User',
            'email' => 'root@carenest.com',
            'role' => Role::ROOT,
        ]);

        // 2. Admin User
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@carenest.com',
            'role' => Role::ADMIN,
        ]);

        // 3. Create Services
        $serviceGP = Service::create([
            'name' => 'General Health Checkup',
            'description' => 'Standard physical examination, checking vital signs, and general health review.',
            'price' => 50.00,
            'required_prepayment' => false,
            'specialization' => Specialization::GENERAL_MEDICINE,
        ]);

        $serviceCardio = Service::create([
            'name' => 'Comprehensive Cardiology Exam',
            'description' => 'ECG test, blood pressure monitoring, and specialized heart health evaluation.',
            'price' => 180.00,
            'required_prepayment' => true,
            'specialization' => Specialization::CARDIOLOGY,
        ]);

        $servicePediatric = Service::create([
            'name' => 'Pediatric Routine Consultation',
            'description' => 'Child development assessment, vaccination review, and general consultation.',
            'price' => 75.00,
            'required_prepayment' => false,
            'specialization' => Specialization::PEDIATRICS,
        ]);

        $serviceDerma = Service::create([
            'name' => 'Skin Allergy Patch Test',
            'description' => 'Specialized test to diagnose skin allergies and inflammatory triggers.',
            'price' => 110.00,
            'required_prepayment' => false,
            'specialization' => Specialization::DERMATOLOGY,
        ]);

        $serviceNeuro = Service::create([
            'name' => 'Neurological Focus Evaluation',
            'description' => 'Testing reflexes, sensory responses, cognitive checks, and nerve function review.',
            'price' => 160.00,
            'required_prepayment' => true,
            'specialization' => Specialization::NEUROLOGY,
        ]);

        // 4. Create Doctors and link them to Services
        $doctorSmithUser = User::factory()->create([
            'name' => 'Dr. Jane Smith',
            'email' => 'doctor.smith@carenest.com',
            'role' => Role::DOCTOR,
        ]);
        $doctorSmith = $doctorSmithUser->doctor()->create([
            'specialization' => Specialization::CARDIOLOGY,
        ]);
        $doctorSmith->services()->attach($serviceCardio);

        $doctorDoeUser = User::factory()->create([
            'name' => 'Dr. John Doe',
            'email' => 'doctor.doe@carenest.com',
            'role' => Role::DOCTOR,
        ]);
        $doctorDoe = $doctorDoeUser->doctor()->create([
            'specialization' => Specialization::PEDIATRICS,
        ]);
        $doctorDoe->services()->attach($servicePediatric);

        $doctorJohnsonUser = User::factory()->create([
            'name' => 'Dr. Alice Johnson',
            'email' => 'doctor.johnson@carenest.com',
            'role' => Role::DOCTOR,
        ]);
        $doctorJohnson = $doctorJohnsonUser->doctor()->create([
            'specialization' => Specialization::DERMATOLOGY,
        ]);
        $doctorJohnson->services()->attach($serviceDerma);

        $doctorBrownUser = User::factory()->create([
            'name' => 'Dr. Sarah Brown',
            'email' => 'doctor.brown@carenest.com',
            'role' => Role::DOCTOR,
        ]);
        $doctorBrown = $doctorBrownUser->doctor()->create([
            'specialization' => Specialization::GENERAL_MEDICINE,
        ]);
        $doctorBrown->services()->attach($serviceGP);

        // 5. Create Patients
        $patientWilsonUser = User::factory()->create([
            'name' => 'Mark Wilson',
            'email' => 'patient.wilson@carenest.com',
            'role' => Role::PATIENT,
        ]);
        $patientWilson = $patientWilsonUser->patient()->create([
            'weight' => 78.50,
            'height' => 182.00,
            'dob' => '1991-04-10',
        ]);

        $patientDavisUser = User::factory()->create([
            'name' => 'Emily Davis',
            'email' => 'patient.davis@carenest.com',
            'role' => Role::PATIENT,
        ]);
        $patientDavis = $patientDavisUser->patient()->create([
            'weight' => 58.00,
            'height' => 164.00,
            'dob' => '1994-08-15',
        ]);

        $patientMillerUser = User::factory()->create([
            'name' => 'Michael Miller',
            'email' => 'patient.miller@carenest.com',
            'role' => Role::PATIENT,
        ]);
        $patientMiller = $patientMillerUser->patient()->create([
            'weight' => 82.50,
            'height' => 178.50,
            'dob' => '1987-12-05',
        ]);

        // 6. Create Schedules (Generates Time Slots automatically)
        $scheduleCardio = Schedule::create([
            'doctor_id' => $doctorSmith->id,
            'date' => now()->format('Y-m-d'),
            'start_time' => '09:00:00',
            'end_time' => '12:00:00',
            'slot_duration_minutes' => 30,
        ]);

        $schedulePediatric = Schedule::create([
            'doctor_id' => $doctorDoe->id,
            'date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '13:00:00',
            'end_time' => '16:00:00',
            'slot_duration_minutes' => 30,
        ]);

        $scheduleDerma = Schedule::create([
            'doctor_id' => $doctorJohnson->id,
            'date' => now()->format('Y-m-d'),
            'start_time' => '10:00:00',
            'end_time' => '13:00:00',
            'slot_duration_minutes' => 30,
        ]);

        $scheduleGP = Schedule::create([
            'doctor_id' => $doctorBrown->id,
            'date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '09:00:00',
            'end_time' => '12:00:00',
            'slot_duration_minutes' => 30,
        ]);

        // 7. Create Appointments and mark Time Slots booked
        
        // Appointment 1: Mark Wilson books with Dr. Sarah Brown (GP)
        $slotGP = $scheduleGP->time_slots()->first();
        if ($slotGP) {
            $slotGP->update(['status' => TimeSlotStatus::BOOKED]);
            
            Appointment::create([
                'patient_id' => $patientWilson->id,
                'doctor_id' => $doctorBrown->id,
                'service_id' => $serviceGP->id,
                'time_slot_id' => $slotGP->id,
                'payment_type' => PaymentType::ONSITE,
                'status' => AppointmentStatus::CONFIRMED,
            ]);
        }

        // Appointment 2: Emily Davis books with Dr. Jane Smith (Cardio)
        $slotCardio = $scheduleCardio->time_slots()->first();
        if ($slotCardio) {
            $slotCardio->update(['status' => TimeSlotStatus::BOOKED]);

            $appointmentCardio = Appointment::create([
                'patient_id' => $patientDavis->id,
                'doctor_id' => $doctorSmith->id,
                'service_id' => $serviceCardio->id,
                'time_slot_id' => $slotCardio->id,
                'payment_type' => PaymentType::ONLINE,
                'status' => AppointmentStatus::CONFIRMED,
            ]);

            Payment::create([
                'amount' => 180.00,
                'method' => PaymentMethod::CARD,
                'status' => PaymentStatus::PAID,
                'appointment_id' => $appointmentCardio->id,
                'paid_at' => '08:45:00',
            ]);
        }

        // Appointment 3: Michael Miller books with Dr. Alice Johnson (Derma)
        $slotDerma = $scheduleDerma->time_slots()->first();
        if ($slotDerma) {
            $slotDerma->update(['status' => TimeSlotStatus::BOOKED]);

            $appointmentDerma = Appointment::create([
                'patient_id' => $patientMiller->id,
                'doctor_id' => $doctorJohnson->id,
                'service_id' => $serviceDerma->id,
                'time_slot_id' => $slotDerma->id,
                'payment_type' => PaymentType::ONLINE,
                'status' => AppointmentStatus::PENDING,
            ]);

            Payment::create([
                'amount' => 110.00,
                'method' => PaymentMethod::QR,
                'status' => PaymentStatus::PENDING,
                'appointment_id' => $appointmentDerma->id,
                'paid_at' => '09:15:00',
            ]);
        }

        // Appointment 4: Emily Davis books with Dr. John Doe (Pediatrician)
        $slotPediatric = $schedulePediatric->time_slots()->first();
        if ($slotPediatric) {
            $slotPediatric->update(['status' => TimeSlotStatus::BOOKED]);

            Appointment::create([
                'patient_id' => $patientDavis->id,
                'doctor_id' => $doctorDoe->id,
                'service_id' => $servicePediatric->id,
                'time_slot_id' => $slotPediatric->id,
                'payment_type' => PaymentType::ONSITE,
                'status' => AppointmentStatus::PENDING,
            ]);
        }

        // Appointment 5: Michael Miller books with Dr. Jane Smith (Cardiologist) - 2nd slot
        $slotCardio2 = $scheduleCardio->time_slots()->skip(1)->first();
        if ($slotCardio2) {
            $slotCardio2->update(['status' => TimeSlotStatus::BOOKED]);

            Appointment::create([
                'patient_id' => $patientMiller->id,
                'doctor_id' => $doctorSmith->id,
                'service_id' => $serviceCardio->id,
                'time_slot_id' => $slotCardio2->id,
                'payment_type' => PaymentType::ONLINE,
                'status' => AppointmentStatus::PENDING,
            ]);
        }

        // Appointment 6: Mark Wilson books with Dr. Alice Johnson (Dermatologist) - 2nd slot
        $slotDerma2 = $scheduleDerma->time_slots()->skip(1)->first();
        if ($slotDerma2) {
            $slotDerma2->update(['status' => TimeSlotStatus::BOOKED]);

            Appointment::create([
                'patient_id' => $patientWilson->id,
                'doctor_id' => $doctorJohnson->id,
                'service_id' => $serviceDerma->id,
                'time_slot_id' => $slotDerma2->id,
                'payment_type' => PaymentType::ONSITE,
                'status' => AppointmentStatus::CONFIRMED,
            ]);
        }
    }
}
