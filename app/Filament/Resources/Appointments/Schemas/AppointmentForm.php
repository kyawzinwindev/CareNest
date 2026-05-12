<?php

namespace App\Filament\Resources\Appointments\Schemas;

use App\Enums\PaymentType;
use App\Enums\Role;
use Filament\Forms\Components\Select;
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
                    ->visible(fn() => in_array(Auth::user()->role, [Role::ROOT, Role::ADMIN])),
                Select::make('service_id')
                    ->relationship(
                        'service',
                        'id'
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn($record) => $record->name
                    )
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make("payment_type")
                    ->options(PaymentType::options())
            ]);
    }
}
