<?php

namespace App\Filament\Resources\Payments\Schemas;

use App\Enums\PaymentStatus;
use App\Enums\PaymentMethod;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id')
                    ->label('ID')
                    ->disabled(),
                TextInput::make('appointment.patient.user.name')
                    ->label('Patient')
                    ->disabled()
                    ->dehydrated(false),
                TextInput::make('amount')
                    ->label('Amount (THB)')
                    ->disabled()
                    ->numeric(),
                Select::make('method')
                    ->label('Payment Method')
                    ->options(PaymentMethod::options())
                    ->disabled(),
                Select::make('status')
                    ->label('Status')
                    ->options(PaymentStatus::options())
                    ->required(),
                FileUpload::make('screenshot')
                    ->label('Receipt')
                    ->disk('public')
                    ->image()
                    ->disabled(),
            ]);
    }
}
