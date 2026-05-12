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
                    ->label('Receipt'),

                TextColumn::make('paid_at')
                    ->dateTime(),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => PaymentStatus::PENDING,
                        'success' => PaymentStatus::PAID,
                        'danger' => PaymentStatus::FAILED,
                    ]),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(PaymentStatus::options()),
                SelectFilter::make('method')
                    ->options(PaymentMethod::options()),
            ])
            ->recordActions([
                Action::make('accept')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn($record) => $record->status === PaymentStatus::PENDING)
                    ->action(function ($record) {
                        $record->update([
                            'status' => PaymentStatus::PAID,
                        ]);

                        $record->appointment->update([
                            'status' => AppointmentStatus::CONFIRMED,
                        ]);
                    }),

                Action::make('reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn($record) => $record->status === PaymentStatus::PENDING)
                    ->action(function ($record) {
                        $record->update([
                            'status' => PaymentStatus::FAILED,
                        ]);
                    }),

                ViewAction::make(),
            ]);
    }
}
