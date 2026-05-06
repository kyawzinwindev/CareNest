<?php

namespace App\Filament\Resources\Specializations\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SpecializationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required(),
                Textarea::make('description')
                    ->rows(10)
                    ->cols(20)
                    ->required()
            ])
            ->columns(1);
    }
}
