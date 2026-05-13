<?php

namespace App\Filament\Resources\Appointments\Schemas;

use App\Enums\PaymentType;
use App\Enums\Role;
use App\Enums\TimeSlotStatus;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class AppointmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('patient_id')
                    ->relationship(
                        'patient',
                        'id',
                        modifyQueryUsing: fn($query) => $query->whereHas(
                            'user',
                            fn($q) => $q->where('role', Role::PATIENT)
                        )
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn($record) => $record->user->name
                    )
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('doctor_id')
                    ->relationship(
                        'doctor',
                        'id',
                        modifyQueryUsing: fn($query) => $query->whereHas(
                            'user',
                            fn($q) => $q->where('role', Role::DOCTOR)
                        )
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn($record) => $record->user->name
                    )
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn(Set $set) => [
                        $set('service_id', null),
                        $set('schedule_id', null),
                        $set('time_slot_id', null),
                    ]),
                Select::make('service_id')
                    ->label("Available Services")
                    ->options(function (Get $get) {

                        $doctorId = $get('doctor_id');

                        if (!$doctorId) {
                            return [];
                        }

                        return \App\Models\Service::query()
                            ->whereHas(
                                'doctors',
                                fn($q) =>
                                $q->where('doctors.id', $doctorId)
                            )
                            ->pluck('name', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('schedule_id')
                    ->label("Available Schedules")
                    ->options(function (Get $get) {

                        $doctorId = $get('doctor_id');

                        if (!$doctorId) {
                            return [];
                        }

                        return \App\Models\Schedule::query()
                            ->where('doctor_id', $doctorId)
                            ->pluck('date', 'id');
                    })
                    ->required()
                    ->live()
                    ->afterStateUpdated(
                        fn(Set $set) =>
                        $set('time_slot_id', null)
                    ),
                Select::make('time_slot_id')
                    ->label("Available Time Slots")
                    ->options(function (Get $get) {

                        $scheduleId = $get('schedule_id');

                        if (!$scheduleId) {
                            return [];
                        }

                        return \App\Models\TimeSlot::query()
                            ->where('schedule_id', $scheduleId)
                            ->where('status', TimeSlotStatus::AVAILABLE)
                            ->get()
                            ->mapWithKeys(fn($slot) => [
                                $slot->id =>
                                $slot->start_time . ' - ' . $slot->end_time
                            ]);
                    })
                    ->searchable()
                    ->required(),
            ]);
    }
}
