<?php

namespace App\Filament\Resources\Payments\Tables;

use App\Enums\AppointmentStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Appointment;
use Filament\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

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

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (PaymentStatus $state): string => match ($state) {
                        PaymentStatus::PENDING => 'warning',
                        PaymentStatus::PENDING_VERIFICATION => 'info',
                        PaymentStatus::PAID => 'success',
                        PaymentStatus::FAILED => 'danger',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(PaymentStatus::options()),
                SelectFilter::make('method')
                    ->options(PaymentMethod::options()),
            ])
            ->actions([
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === PaymentStatus::PENDING_VERIFICATION)
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        DB::transaction(function () use ($record) {
                            $record->update([
                                'status' => PaymentStatus::PAID,
                                'paid_at' => now(),
                            ]);
                            $record->appointment->update([
                                'status' => AppointmentStatus::CONFIRMED,
                            ]);
                        });
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Payment Approved')
                            ->body('The payment has been marked as Paid and the appointment is now Confirmed.')
                            ->success()
                            ->send();
                    }),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status === PaymentStatus::PENDING_VERIFICATION)
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        DB::transaction(function () use ($record) {
                            $record->update([
                                'status' => PaymentStatus::FAILED,
                            ]);
                            $record->appointment->update([
                                'status' => AppointmentStatus::CANCELLED,
                            ]);
                        });

                        \Filament\Notifications\Notification::make()
                            ->title('Payment Rejected')
                            ->body('The payment has been marked as Failed and the appointment is now Cancelled.')
                            ->danger()
                            ->send();
                    }),
            ]);
    }
}
