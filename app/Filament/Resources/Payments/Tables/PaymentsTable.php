<?php

namespace App\Filament\Resources\Payments\Tables;

use App\Enums\AppointmentStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Appointment;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('ID'),
                TextColumn::make('appointment.patient.user.name')
                    ->label('Patient')
                    ->searchable(),

                TextColumn::make('amount')
                    ->money('THB'),

                TextColumn::make('method')
                    ->badge(),

                ImageColumn::make('screenshot')
                    ->label('Receipt')
                    ->disk('public')
                    ->url(fn($record) => $record->screenshot ? '/storage/' . $record->screenshot : null, shouldOpenInNewTab: true),

                TextColumn::make('paid_at')
                    ->dateTime(),

                SelectColumn::make('status')
                    ->options(PaymentStatus::options()),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(PaymentStatus::options()),
                SelectFilter::make('method')
                    ->options(PaymentMethod::options()),
            ])
            ->actions([
                //
            ]);
    }
}
