<?php

namespace App\Filament\Widgets;

use App\Enums\AppointmentStatus;
use App\Enums\Role;
use App\Enums\TimeSlotStatus;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\TimeSlot;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $user = auth()->user();

        if ($user->role === Role::DOCTOR) {
            $doctorId = $user->doctor?->id;

            $todayAppointments = Appointment::where('doctor_id', $doctorId)
                ->whereHas('time_slot.schedule', fn($q) => $q->where('date', now()->format('Y-m-d')))
                ->count();

            $pendingAppointments = Appointment::where('doctor_id', $doctorId)
                ->where('status', AppointmentStatus::PENDING)
                ->count();

            $todaySlots = TimeSlot::where('status', TimeSlotStatus::AVAILABLE)
                ->whereHas('schedule', fn($q) => $q->where('doctor_id', $doctorId)->where('date', now()->format('Y-m-d')))
                ->count();

            return [
                Stat::make("My Today's Appointments", $todayAppointments)
                    ->description('Scheduled for today')
                    ->descriptionIcon('heroicon-m-calendar')
                    ->color('success'),
                Stat::make("My Pending Appointments", $pendingAppointments)
                    ->description('Awaiting confirmation')
                    ->descriptionIcon('heroicon-m-clock')
                    ->color('warning'),
                Stat::make("My Today's Available Time Slots", $todaySlots)
                    ->description('Available slots for today')
                    ->descriptionIcon('heroicon-m-clock')
                    ->color('info'),
            ];
        }

        $todayAppointments = Appointment::whereHas('time_slot.schedule', fn($q) => $q->where('date', now()->format('Y-m-d')))->count();
        $pendingAppointments = Appointment::where('status', AppointmentStatus::PENDING)->count();
        $doctorsCount = Doctor::count();
        $patientsCount = Patient::count();

        return [
            Stat::make("Today's Appointments", $todayAppointments)
                ->description('Total scheduled for today')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('success'),
            Stat::make("Pending Appointments", $pendingAppointments)
                ->description('Awaiting review')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make("Doctors Registered", $doctorsCount)
                ->description('Total medical staff')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
            Stat::make("Patients Registered", $patientsCount)
                ->description('Total registered patients')
                ->descriptionIcon('heroicon-m-user')
                ->color('primary'),
        ];
    }
}
