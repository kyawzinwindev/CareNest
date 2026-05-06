<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\Role;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required(),
                TextInput::make('email')->required(),
                TextInput::make('password')
                    ->password()
                    ->required(fn($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                    ->dehydrateStateUsing(fn($state) => filled($state) ? bcrypt($state) : null)
                    ->dehydrated(fn($state) => filled($state))
                    ->label('Password'),
                Select::make('role')
                    ->options(Role::options())
                    ->default(Role::PATIENT->value)
                    ->required()
                    ->live(),
                Select::make('specialization_id')
                    ->label("Doctor Specialization")
                    ->relationship('doctor.specialization', 'name')
                    ->required()
                    ->visible(fn (Get $get) => $get('role') === Role::DOCTOR->value)
            ])
            ->columns(1);
    }
}
