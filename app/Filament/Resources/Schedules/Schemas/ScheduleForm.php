<?php

namespace App\Filament\Resources\Schedules\Schemas;

use App\Enums\Role;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class ScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                    
                DatePicker::make('date')
                    ->required(),

                TimePicker::make('start_time')
                    ->seconds(false)
                    ->before('end_time')
                    ->required(),

                TimePicker::make('end_time')
                    ->seconds(false)
                    ->after('start_time')
                    ->required(),

                TextInput::make('slot_duration_minutes')
                    ->numeric()
                    ->default(30)
                    ->suffix('minutes')
                    ->required(),
            ]);
    }
}
