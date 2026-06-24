<?php

namespace App\Filament\Resources\Services\Schemas;

use App\Enums\Specialization;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required(),
                Textarea::make('description')
                    ->rows(10)
                    ->cols(20)
                    ->required(),
                TextInput::make('price')
                    ->numeric()
                    ->required(),
                Select::make('specialization')
                    ->options(Specialization::class)
                    ->required(),
            ])
            ->columns(1);
    }
}

