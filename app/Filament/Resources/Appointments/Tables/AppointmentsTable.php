<?php

namespace App\Filament\Resources\Appointments\Tables;

use App\Enums\AppointmentStatus;
use App\Enums\TimeSlotStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\QueryException;

class AppointmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("id")->label("ID"),
                TextColumn::make("patient.user.name")
                    ->label("Patient"),
                TextColumn::make("doctor.user.name")
                    ->label("Doctor"),
                TextColumn::make("time_slot.schedule.date")
                    ->label("Schedule Date"),
                TextColumn::make("service.name")
                    ->label("Service"),
                TextColumn::make("time_slot.start_time")->label("Start Time"),
                TextColumn::make("time_slot.end_time")->label("End Time"),
                TextColumn::make('payment_type')
                    ->label("Payment Type")
                    ->badge(),
                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => AppointmentStatus::PENDING->value,
                        'success' => AppointmentStatus::CONFIRMED->value,
                        'danger' => AppointmentStatus::CANCELLED->value,
                    ]),
            ])
            ->filters([
                SelectFilter::make("doctor")
                    ->relationship(
                        'doctor',
                        'id',
                        fn($query) => $query->with('user')
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn($record) => $record->user->name
                    )
                    ->searchable()
                    ->preload(),

                SelectFilter::make("patient")
                    ->relationship(
                        'patient',
                        'id',
                        fn($query) => $query->with('user')
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn($record) => $record->user->name
                    )
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                // EditAction::make(),
                DeleteAction::make()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
