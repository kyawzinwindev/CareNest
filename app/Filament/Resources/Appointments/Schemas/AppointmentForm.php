<?php

namespace App\Filament\Resources\Appointments\Schemas;

use App\Enums\PaymentMethod;
use App\Enums\Role;
use App\Enums\TimeSlotStatus;
use App\Enums\AppointmentStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Textarea;
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
                    ->required()
                    ->disabled(fn ($record) => auth()->user()->role === Role::DOCTOR || ($record && in_array($record->status, [AppointmentStatus::FINISHED, AppointmentStatus::CANCELLED]))),
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
                    ->default(fn() => auth()->user()?->role === Role::DOCTOR ? auth()->user()?->doctor?->id : null)
                    ->disabled(fn ($record) => auth()->user()?->role === Role::DOCTOR || ($record && in_array($record->status, [AppointmentStatus::FINISHED, AppointmentStatus::CANCELLED])))
                    ->dehydrated()
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
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Set $set, $state) {
                        if ($state) {
                            $service = \App\Models\Service::find($state);
                            $set('payment_amount', $service?->price);
                        } else {
                            $set('payment_amount', null);
                        }
                    })
                    ->disabled(fn ($record) => auth()->user()->role === Role::DOCTOR || ($record && in_array($record->status, [AppointmentStatus::FINISHED, AppointmentStatus::CANCELLED]))),
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
                    )
                    ->disabled(fn ($record) => auth()->user()->role === Role::DOCTOR || ($record && in_array($record->status, [AppointmentStatus::FINISHED, AppointmentStatus::CANCELLED]))),
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
                    ->required()
                    ->disabled(fn ($record) => auth()->user()->role === Role::DOCTOR || ($record && in_array($record->status, [AppointmentStatus::FINISHED, AppointmentStatus::CANCELLED]))),
                Section::make('Payment Information')
                    ->description('Provide payment details for this appointment.')
                    ->schema([
                        Select::make('payment_method')
                            ->label('Payment Method')
                            ->options(PaymentMethod::options())
                            ->required(),
                        TextInput::make('payment_amount')
                            ->label('Amount')
                            ->numeric()
                            ->prefix('$')
                            ->required(),
                        FileUpload::make('payment_screenshot')
                            ->label('Payslip Upload (Screenshot)')
                            ->image()
                            ->directory('payments')
                            ->visibility('public')
                            ->required(),
                    ])
                    ->visible(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord),
                Section::make('Consultation & Prescription')
                    ->description('Record clinical findings, prescriptions, and manage appointment completion.')
                    ->schema([
                        Textarea::make('prescription')
                            ->label('Prescription / Clinical Notes')
                            ->rows(5)
                            ->placeholder('Enter prescription details, dosage, and patient advice...')
                            ->required(fn (Get $get) => $get('status') === AppointmentStatus::FINISHED->value)
                            ->disabled(function ($record) {
                                if ($record && in_array($record->status, [AppointmentStatus::FINISHED, AppointmentStatus::CANCELLED])) {
                                    return true;
                                }
                                $user = auth()->user();
                                if ($user->role === Role::DOCTOR) {
                                    return !$record || $record->doctor_id !== $user->doctor?->id;
                                }
                                return true; // Admins cannot edit prescription notes
                            })
                            ->dehydrated(),
                        Select::make('status')
                            ->label('Appointment Status')
                            ->options(AppointmentStatus::options())
                            ->required()
                            ->disabled(function ($record) {
                                if ($record && in_array($record->status, [AppointmentStatus::FINISHED, AppointmentStatus::CANCELLED])) {
                                    return true;
                                }
                                $user = auth()->user();
                                if ($user->role === Role::DOCTOR) {
                                    return !$record || $record->doctor_id !== $user->doctor?->id;
                                }
                                return false; // Admins can change status
                            })
                            ->dehydrated(),
                    ])
                    ->visible(fn ($record) => $record !== null),
            ]);
    }
}
