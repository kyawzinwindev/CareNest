<?php

namespace App\Livewire\BookingWizard;

use App\Enums\AppointmentStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\Role;
use App\Enums\Specialization;
use App\Enums\TimeSlotStatus;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Schedule;
use App\Models\Service;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;
use Carbon\Carbon;

class BookingWizard extends Component
{
    use WithFileUploads;

    // Wizard navigation state
    public int $step = 1;

    // Step 1 selections
    public ?string $specialization = null;
    public ?int $serviceId = null;

    // Step 2 selections
    public ?int $doctorId = null;

    // Step 3 selections
    public ?string $date = null;
    public ?int $timeSlotId = null;

    // Step 4 User & Patient info
    public string $paymentType = 'online'; // Always online in the new flow
    public bool $isLogin = false; // toggle between login & register
    public string $loginEmail = '';
    public string $loginPassword = '';

    public string $registerName = '';
    public string $registerEmail = '';
    public string $registerPassword = '';

    public string $dob = '';
    public string $weight = '';
    public string $height = '';

    // Step 5 Online Payment info
    public string $paymentMethod = 'card';
    public $screenshot;

    // Query parameters / URL properties
    protected $queryString = ['specialization'];

    public function mount()
    {
        if (Auth::check() && Auth::user()->role !== Role::PATIENT) {
            return $this->redirect('/admin');
        }

        // Pre-fill specialization if passed in URL query
        if ($this->specialization) {
            $valid = collect(Specialization::cases())->map(fn($c) => $c->value)->contains($this->specialization);
            if (!$valid) {
                $this->specialization = null;
            }
        }

        // If authenticated patient, pre-fill some metrics
        if (Auth::check() && Auth::user()->role === Role::PATIENT) {
            $patient = Auth::user()->patient;
            if ($patient) {
                $this->dob = $patient->dob
    ? Carbon::parse($patient->dob)->format('Y-m-d')
    : '';
                $this->weight = $patient->weight ?? '';
                $this->height = $patient->height ?? '';
            }
        }
    }

    public function getServicesProperty()
    {
        if (!$this->specialization) {
            return collect();
        }
        return Service::where('specialization', $this->specialization)->get();
    }

    public function getSelectedServiceProperty()
    {
        return $this->serviceId ? Service::find($this->serviceId) : null;
    }

    public function getDoctorsProperty()
    {
        if (!$this->specialization) {
            return collect();
        }
        // Doctors with matching specialization
        return Doctor::where('specialization', $this->specialization)->with('user')->get();
    }

    public function getSelectedDoctorProperty()
    {
        return $this->doctorId ? Doctor::find($this->doctorId) : null;
    }

    public function getAvailableDatesProperty()
    {
        if (!$this->doctorId) {
            return collect();
        }
        // Return distinct dates of future schedules for this doctor
        return Schedule::where('doctor_id', $this->doctorId)
            ->where('date', '>=', now()->format('Y-m-d'))
            ->orderBy('date', 'asc')
            ->pluck('date');
    }

    public function getTimeSlotsProperty()
    {
        if (!$this->doctorId || !$this->date) {
            return collect();
        }

        $schedule = Schedule::where('doctor_id', $this->doctorId)
            ->where('date', $this->date)
            ->first();

        if (!$schedule) {
            return collect();
        }

        return TimeSlot::where('schedule_id', $schedule->id)
            ->orderBy('start_time', 'asc')
            ->get();
    }

    public function getSelectedTimeSlotProperty()
    {
        return $this->timeSlotId ? TimeSlot::find($this->timeSlotId) : null;
    }

    public function selectSpecialization($value)
    {
        $this->specialization = $value;
        $this->serviceId = null;
        $this->doctorId = null;
        $this->date = null;
        $this->timeSlotId = null;
    }

    public function selectService($id)
    {
        $this->serviceId = $id;
        $this->doctorId = null;
        $this->date = null;
        $this->timeSlotId = null;
        $this->goToStep(2);
    }

    public function selectDoctor($id)
    {
        $this->doctorId = $id;
        $this->date = null;
        $this->timeSlotId = null;

        // Pre-select first available date
        $dates = $this->availableDates;
        if ($dates->count() > 0) {
            $this->date = $dates->first();
        }

        $this->goToStep(3);
    }

    public function selectTimeSlot($id)
    {
        $slot = TimeSlot::find($id);
        if ($slot && $slot->status === TimeSlotStatus::AVAILABLE) {
            $this->timeSlotId = $id;
        }
    }

    public function goToStep($targetStep)
    {
        if ($targetStep < $this->step) {
            $this->step = $targetStep;
            return;
        }

        if ($targetStep === 2) {
            $this->validate([
                'specialization' => 'required',
                'serviceId' => 'required|exists:services,id',
            ]);
        }

        if ($targetStep === 3) {
            $this->validate([
                'doctorId' => 'required|exists:doctors,id',
            ]);
        }

        if ($targetStep === 4) {
            $this->validate([
                'date' => 'required|date',
                'timeSlotId' => 'required|exists:time_slots,id',
            ]);
        }

        if ($targetStep === 5) {
            $this->handleStep4Validation();
        }

        $this->step = $targetStep;
    }

    protected function handleStep4Validation()
    {
        if (Auth::check()) {
            $this->validate([
                'dob' => 'required|date',
                'weight' => 'required|numeric|min:1',
                'height' => 'required|numeric|min:1',
            ]);
        } else {
            if ($this->isLogin) {
                $this->validate([
                    'loginEmail' => 'required|email',
                    'loginPassword' => 'required',
                ]);

                if (!Auth::attempt(['email' => $this->loginEmail, 'password' => $this->loginPassword])) {
                    $this->addError('loginEmail', 'These credentials do not match our records.');
                    throw new \Illuminate\Validation\ValidationException($this);
                }

                session()->regenerate();

                if (Auth::user()->role !== Role::PATIENT) {
                    return $this->redirect('/admin');
                }

                // Load patient info if patient exists
                $patient = Auth::user()->patient;
                if ($patient) {
                    $this->dob = $patient->dob ? $patient->dob->format('Y-m-d') : '';
                    $this->weight = $patient->weight ?? '';
                    $this->height = $patient->height ?? '';
                }
            } else {
                $this->validate([
                    'registerName' => 'required|string|max:255',
                    'registerEmail' => 'required|email|unique:users,email',
                    'registerPassword' => 'required|min:8',
                    'dob' => 'required|date',
                    'weight' => 'required|numeric|min:1',
                    'height' => 'required|numeric|min:1',
                ]);

                $user = User::create([
                    'name' => $this->registerName,
                    'email' => $this->registerEmail,
                    'password' => Hash::make($this->registerPassword),
                    'role' => Role::PATIENT,
                ]);

                Patient::create([
                    'user_id' => $user->id,
                    'dob' => $this->dob,
                    'weight' => $this->weight,
                    'height' => $this->height,
                ]);

                Auth::login($user);
            }
        }
    }

    public function submitBooking()
    {
        $this->handleStep4Validation();

        // Enforce online payment validation (card/qr and screenshot proof are mandatory)
        $this->validate([
            'paymentMethod' => 'required|in:card,qr',
            'screenshot' => 'required|image|max:5120', // Max 5MB
        ]);

        try {
            DB::transaction(function () {
                $timeSlot = TimeSlot::where('id', $this->timeSlotId)
                    ->lockForUpdate()
                    ->first();

                if (!$timeSlot || $timeSlot->status !== TimeSlotStatus::AVAILABLE) {
                    throw new \Exception('This time slot has already been booked by another user.');
                }

                $user = Auth::user();
                // Ensure patient profile is created or updated
                $patient = $user->patient;
                if (!$patient) {
                    $patient = Patient::create([
                        'user_id' => $user->id,
                        'dob' => $this->dob,
                        'weight' => $this->weight,
                        'height' => $this->height,
                    ]);
                } else {
                    $patient->update([
                        'dob' => $this->dob,
                        'weight' => $this->weight,
                        'height' => $this->height,
                    ]);
                }

                $appointment = Appointment::create([
                    'patient_id' => $patient->id,
                    'doctor_id' => $this->doctorId,
                    'service_id' => $this->serviceId,
                    'time_slot_id' => $this->timeSlotId,
                    'status' => AppointmentStatus::PENDING,
                ]);

                $timeSlot->update([
                    'status' => TimeSlotStatus::BOOKED
                ]);

                $screenshotPath = $this->screenshot->store('payments', 'public');
                Payment::create([
                    'appointment_id' => $appointment->id,
                    'amount' => $this->selectedService->price,
                    'method' => PaymentMethod::from($this->paymentMethod),
                    'status' => PaymentStatus::PENDING_VERIFICATION,
                    'screenshot' => $screenshotPath,
                    'paid_at' => null, // Left null until Admin verifies and approves payment
                ]);
            });

            session()->flash('message', 'Appointment booked successfully!');
            return redirect()->to('/');

        } catch (\Exception $e) {
            $this->addError('timeSlotId', $e->getMessage());
            $this->step = 3; // Redirect back to time slot selection
        }
    }

    public function render()
    {
        return view('livewire.booking-wizard')
            ->layout('components.layouts.app');
    }
}
