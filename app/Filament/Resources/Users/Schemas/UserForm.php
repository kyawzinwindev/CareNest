<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\Role;
use App\Enums\Specialization;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

use function Laravel\Prompts\title;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required(),
                TextInput::make('email')
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->required(fn($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                    ->dehydrateStateUsing(fn($state) => filled($state) ? bcrypt($state) : null)
                    ->dehydrated(fn($state) => filled($state))
                    ->label('Password'),
                Select::make('role')
                    ->options(Role::optionsBasedOnUserRole())
                    ->required()
                    ->live(),
                Group::make()
                    ->relationship('doctor')
                    ->schema([
                        Select::make('specialization')
                            ->label("Doctor Specialization")
                            ->options(Specialization::class)
                            ->required()
                            ->live(),

                        CheckboxList::make('services')
                            ->relationship(
                                name: 'services',
                                titleAttribute: 'name'
                            )
                            ->options(function (Get $get) {
                                $specialization = $get('specialization');

                                if (!$specialization) {
                                    return [];
                                }

                                return \App\Models\Service::query()
                                    ->where('specialization', $specialization)
                                    ->pluck('name', 'id');
                            })
                            ->columns(2)
                            ->searchable()
                            ->required(),
                    ])
                    ->visible(fn(Get $get) => $get('role') == Role::DOCTOR->value),
                Group::make()
                    ->relationship('patient')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('weight')
                                    ->label('Weight (kg)')
                                    ->numeric()
                                    ->required(),
                                TextInput::make('height')
                                    ->label('Height (cm)')
                                    ->numeric()
                                    ->required(),
                                DatePicker::make('dob')
                                    ->label('Date of Birth')
                                    ->required(),
                            ]),
                    ])
                    ->visible(fn(Get $get) => $get('role') == Role::PATIENT->value),
            ])
            ->columns(1);
    }
}
