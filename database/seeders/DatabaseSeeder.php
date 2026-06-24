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
use App\Enums\PaymentStatus;
use App\Enums\PaymentMethod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Root User
        User::create([
            'name' => 'Root User',
            'email' => 'root@carenest.com',
            'password' => Hash::make('password'),
            'role' => Role::ROOT,
        ]);

        // 2. Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@carenest.com',
            'password' => Hash::make('password'),
            'role' => Role::ADMIN,
        ]);

        // 3. Create Services (10 Services distributed across various specializations)
        $servicesData = [
            [
                'name' => 'General Consultation',
                'description' => 'Routine general medical checkup, history taking, and prescription guidance.',
                'price' => 40.00,
                'specialization' => Specialization::GENERAL_MEDICINE,
            ],
            [
                'name' => 'Comprehensive Annual Checkup',
                'description' => 'Thorough medical assessment, vital signs screening, and blood report analysis.',
                'price' => 120.00,
                'specialization' => Specialization::GENERAL_MEDICINE,
            ],
            [
                'name' => 'Electrocardiogram (ECG) Evaluation',
                'description' => 'Advanced heart activity monitoring and specialized cardiological feedback.',
                'price' => 150.00,
                'specialization' => Specialization::CARDIOLOGY,
            ],
            [
                'name' => 'Pediatric Well-Child Exam',
                'description' => 'Physical growth assessment, milestones tracking, and general health review for children.',
                'price' => 75.00,
                'specialization' => Specialization::PEDIATRICS,
            ],
            [
                'name' => 'Acne & Skin Lesion Consultation',
                'description' => 'Specialized dermatology consultation focusing on skincare, acne treatment, and diagnostic patches.',
                'price' => 90.00,
                'specialization' => Specialization::DERMATOLOGY,
            ],
            [
                'name' => 'Neurological Reflex & EEG Review',
                'description' => 'Comprehensive assessment of neurological systems, sensory testing, and electroencephalogram readings.',
                'price' => 250.00,
                'specialization' => Specialization::NEUROLOGY,
            ],
            [
                'name' => 'Joint Pain & Orthopedic Exam',
                'description' => 'Evaluation of muscle pain, bone fractures, joints alignment, and physical mobility tests.',
                'price' => 110.00,
                'specialization' => Specialization::ORTHOPEDICS,
            ],
            [
                'name' => 'Routine Eye & Vision Test',
                'description' => 'Ophthalmological screening including visual acuity tests and eyeglasses check.',
                'price' => 60.00,
                'specialization' => Specialization::OPHTHALMOLOGY,
            ],
            [
                'name' => 'Prenatal Wellness Consultation',
                'description' => 'Clinical gynecological checkup monitoring pregnancy progression, ultrasound readings, and fetal vitals.',
                'price' => 130.00,
                'specialization' => Specialization::GYNECOLOGY,
            ],
            [
                'name' => 'Pediatric Vaccination Consultation',
                'description' => 'Consultation mapping child immunizations and minor health follow-ups.',
                'price' => 50.00,
                'specialization' => Specialization::PEDIATRICS,
            ],
        ];

        $services = [];
        foreach ($servicesData as $data) {
            $services[] = Service::create($data);
        }

        // 4. Create 10 Doctors and Link to matching Services
        $doctorsData = [
            ['name' => 'Dr. Jane Smith', 'email' => 'doctor.smith@carenest.com', 'spec' => Specialization::CARDIOLOGY],
            ['name' => 'Dr. John Doe', 'email' => 'doctor.doe@carenest.com', 'spec' => Specialization::PEDIATRICS],
            ['name' => 'Dr. Alice Johnson', 'email' => 'doctor.johnson@carenest.com', 'spec' => Specialization::DERMATOLOGY],
            ['name' => 'Dr. Sarah Brown', 'email' => 'doctor.brown@carenest.com', 'spec' => Specialization::GENERAL_MEDICINE],
            ['name' => 'Dr. Robert Davis', 'email' => 'doctor.davis@carenest.com', 'spec' => Specialization::NEUROLOGY],
            ['name' => 'Dr. Emily Wilson', 'email' => 'doctor.wilson@carenest.com', 'spec' => Specialization::ORTHOPEDICS],
            ['name' => 'Dr. Michael Taylor', 'email' => 'doctor.taylor@carenest.com', 'spec' => Specialization::GYNECOLOGY],
            ['name' => 'Dr. William Thomas', 'email' => 'doctor.thomas@carenest.com', 'spec' => Specialization::OPHTHALMOLOGY],
            ['name' => 'Dr. Jessica White', 'email' => 'doctor.white@carenest.com', 'spec' => Specialization::GENERAL_MEDICINE],
            ['name' => 'Dr. David Martin', 'email' => 'doctor.martin@carenest.com', 'spec' => Specialization::PEDIATRICS],
        ];

        $doctors = [];
        foreach ($doctorsData as $index => $doc) {
            $user = User::create([
                'name' => $doc['name'],
                'email' => $doc['email'],
                'password' => Hash::make('password'),
                'role' => Role::DOCTOR,
            ]);

            $doctor = $user->doctor()->create([
                'specialization' => $doc['spec'],
            ]);

            // Link services with doctor's specialization
            $matchedServices = collect($services)->filter(fn($s) => $s->specialization === $doc['spec']);
            foreach ($matchedServices as $service) {
                $doctor->services()->attach($service);
            }

            $doctors[] = $doctor;
        }

        // 5. Create 12 Patients
        $patientsData = [
            ['name' => 'Mark Wilson', 'email' => 'patient.wilson@carenest.com', 'w' => 78.5, 'h' => 182.0, 'dob' => '1991-04-10'],
            ['name' => 'Emily Davis', 'email' => 'patient.davis@carenest.com', 'w' => 58.0, 'h' => 164.0, 'dob' => '1994-08-15'],
            ['name' => 'Michael Miller', 'email' => 'patient.miller@carenest.com', 'w' => 82.5, 'h' => 178.5, 'dob' => '1987-12-05'],
            ['name' => 'Sarah Jenkins', 'email' => 'patient.jenkins@carenest.com', 'w' => 64.0, 'h' => 170.0, 'dob' => '1995-10-22'],
            ['name' => 'David Clark', 'email' => 'patient.clark@carenest.com', 'w' => 91.2, 'h' => 185.0, 'dob' => '1989-02-14'],
            ['name' => 'Jessica Lewis', 'email' => 'patient.lewis@carenest.com', 'w' => 54.5, 'h' => 160.0, 'dob' => '1993-11-30'],
            ['name' => 'Daniel Hall', 'email' => 'patient.hall@carenest.com', 'w' => 88.0, 'h' => 180.0, 'dob' => '1985-05-18'],
            ['name' => 'Taylor Allen', 'email' => 'patient.allen@carenest.com', 'w' => 70.0, 'h' => 174.0, 'dob' => '1992-07-09'],
            ['name' => 'Ashley Young', 'email' => 'patient.young@carenest.com', 'w' => 61.5, 'h' => 167.5, 'dob' => '1996-03-27'],
            ['name' => 'James King', 'email' => 'patient.king@carenest.com', 'w' => 76.8, 'h' => 176.0, 'dob' => '1990-09-03'],
            ['name' => 'Linda Wright', 'email' => 'patient.wright@carenest.com', 'w' => 55.0, 'h' => 162.0, 'dob' => '1988-12-12'],
            ['name' => 'Patricia Hill', 'email' => 'patient.hill@carenest.com', 'w' => 68.2, 'h' => 169.0, 'dob' => '1991-01-25'],
        ];

        $patients = [];
        foreach ($patientsData as $pat) {
            $user = User::create([
                'name' => $pat['name'],
                'email' => $pat['email'],
                'password' => Hash::make('password'),
                'role' => Role::PATIENT,
            ]);

            $patients[] = $user->patient()->create([
                'weight' => $pat['w'],
                'height' => $pat['h'],
                'dob' => $pat['dob'],
            ]);
        }

        // 6. Create 15 Schedules (Time slots generated automatically)
        $schedulesData = [
            // Today Schedules
            ['doc' => 0, 'date' => now()->format('Y-m-d'), 'start' => '09:00:00', 'end' => '12:00:00'], // Smith (Cardiology)
            ['doc' => 1, 'date' => now()->format('Y-m-d'), 'start' => '13:00:00', 'end' => '16:00:00'], // Doe (Pediatrics)
            ['doc' => 2, 'date' => now()->format('Y-m-d'), 'start' => '10:00:00', 'end' => '13:00:00'], // Johnson (Dermatology)
            ['doc' => 3, 'date' => now()->format('Y-m-d'), 'start' => '09:00:00', 'end' => '12:00:00'], // Brown (Gen Med)
            ['doc' => 4, 'date' => now()->format('Y-m-d'), 'start' => '14:00:00', 'end' => '17:00:00'], // Davis (Neurology)
            
            // Tomorrow Schedules
            ['doc' => 1, 'date' => now()->addDay()->format('Y-m-d'), 'start' => '09:00:00', 'end' => '12:00:00'], // Doe
            ['doc' => 5, 'date' => now()->addDay()->format('Y-m-d'), 'start' => '09:00:00', 'end' => '12:00:00'], // Wilson (Orthopedics)
            ['doc' => 6, 'date' => now()->addDay()->format('Y-m-d'), 'start' => '10:00:00', 'end' => '13:00:00'], // Taylor (Gynecology)
            ['doc' => 7, 'date' => now()->addDay()->format('Y-m-d'), 'start' => '14:00:00', 'end' => '17:00:00'], // Thomas (Ophthalmology)
            ['doc' => 8, 'date' => now()->addDay()->format('Y-m-d'), 'start' => '09:00:00', 'end' => '12:00:00'], // White (Gen Med)
            
            // Day After Tomorrow Schedules
            ['doc' => 0, 'date' => now()->addDays(2)->format('Y-m-d'), 'start' => '09:00:00', 'end' => '12:00:00'], // Smith
            ['doc' => 2, 'date' => now()->addDays(2)->format('Y-m-d'), 'start' => '13:00:00', 'end' => '16:00:00'], // Johnson
            ['doc' => 9, 'date' => now()->addDays(2)->format('Y-m-d'), 'start' => '10:00:00', 'end' => '13:00:00'], // Martin (Pediatrics)
            ['doc' => 3, 'date' => now()->addDays(2)->format('Y-m-d'), 'start' => '09:00:00', 'end' => '12:00:00'], // Brown
            ['doc' => 4, 'date' => now()->addDays(2)->format('Y-m-d'), 'start' => '14:00:00', 'end' => '17:00:00'], // Davis
        ];

        $schedules = [];
        foreach ($schedulesData as $sch) {
            $schedules[] = Schedule::create([
                'doctor_id' => $doctors[$sch['doc']]->id,
                'date' => $sch['date'],
                'start_time' => $sch['start'],
                'end_time' => $sch['end'],
                'slot_duration_minutes' => 30,
            ]);
        }

        // We mark some random slots as UNAVAILABLE to simulate real clinical schedules
        foreach ($schedules as $s) {
            $randomSlot = $s->time_slots()->skip(2)->first();
            if ($randomSlot) {
                $randomSlot->update(['status' => TimeSlotStatus::UNAVAILABLE]);
            }
        }

        // 7. Create at least 12 Appointments & 12 Payment records (Online Prepayment Only)
        // Appointment mapping structure: [patient_index, doctor_index, service_index, schedule_index, slot_offset, status, payment_method, payment_status, screenshot, prescription]
        $appointmentsMapping = [
            // 1. Finished appointment with prescription
            [0, 3, 0, 3, 0, AppointmentStatus::FINISHED, PaymentMethod::CARD, PaymentStatus::PAID, null, 'Patient presented with mild fever. Advised bed rest and Paracetamol 500mg.'], 
            // 2. Online paid
            [1, 0, 2, 0, 0, AppointmentStatus::CONFIRMED, PaymentMethod::CARD, PaymentStatus::PAID, null, null],
            // 3. Online pending QR (pending verification)
            [2, 2, 4, 2, 0, AppointmentStatus::PENDING, PaymentMethod::QR, PaymentStatus::PENDING_VERIFICATION, 'payments/mock_qr_1.png', null],
            // 4. Online failed card (cancelled)
            [3, 1, 3, 1, 0, AppointmentStatus::CANCELLED, PaymentMethod::CARD, PaymentStatus::FAILED, null, null],
            // 5. Online pending QR (pending verification)
            [4, 4, 5, 4, 0, AppointmentStatus::PENDING, PaymentMethod::QR, PaymentStatus::PENDING_VERIFICATION, 'payments/mock_qr_2.png', null],
            // 6. Finished appointment with prescription
            [5, 5, 6, 6, 1, AppointmentStatus::FINISHED, PaymentMethod::CARD, PaymentStatus::PAID, 'payments/mock_card_1.png', 'Follow-up for heart rate monitoring. Readings are stable. Continue current medication.'],
            // 7. Online pending QR (pending verification)
            [6, 6, 8, 7, 0, AppointmentStatus::PENDING, PaymentMethod::QR, PaymentStatus::PENDING_VERIFICATION, 'payments/mock_qr_3.png', null],
            // 8. Online pending QR (pending verification)
            [7, 7, 7, 8, 1, AppointmentStatus::PENDING, PaymentMethod::QR, PaymentStatus::PENDING_VERIFICATION, 'payments/mock_qr_6.png', null],
            // 9. Online paid card
            [8, 8, 1, 9, 0, AppointmentStatus::CONFIRMED, PaymentMethod::CARD, PaymentStatus::PAID, 'payments/mock_card_2.png', null],
            // 10. Online pending QR (pending verification)
            [9, 9, 9, 12, 0, AppointmentStatus::PENDING, PaymentMethod::QR, PaymentStatus::PENDING_VERIFICATION, 'payments/mock_qr_4.png', null],
            // 11. Online paid card
            [10, 0, 2, 10, 1, AppointmentStatus::CONFIRMED, PaymentMethod::CARD, PaymentStatus::PAID, null, null],
            // 12. Online pending QR (pending verification)
            [11, 2, 4, 11, 1, AppointmentStatus::PENDING, PaymentMethod::QR, PaymentStatus::PENDING_VERIFICATION, 'payments/mock_qr_5.png', null],
        ];

        foreach ($appointmentsMapping as $map) {
            $patient = $patients[$map[0]];
            $doctor = $doctors[$map[1]];
            $service = $services[$map[2]];
            $schedule = $schedules[$map[3]];
            $slot = $schedule->time_slots()->skip($map[4])->first();

            if ($slot) {
                $slot->update(['status' => TimeSlotStatus::BOOKED]);

                $appointment = Appointment::create([
                    'patient_id' => $patient->id,
                    'doctor_id' => $doctor->id,
                    'service_id' => $service->id,
                    'time_slot_id' => $slot->id,
                    'status' => $map[5],
                    'prescription' => $map[9],
                ]);

                // Create payment as every appointment strictly requires payment
                Payment::create([
                    'amount' => $service->price,
                    'method' => $map[6],
                    'status' => $map[7],
                    'screenshot' => $map[8],
                    'appointment_id' => $appointment->id,
                    'paid_at' => $map[7] === PaymentStatus::PAID ? Carbon::now()->subMinutes(15) : null,
                ]);
            }
        }
    }
}
