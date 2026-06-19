<?php

namespace App\Filament\Resources\Appointments\Tables;

use App\Enums\AppointmentStatus;
use App\Enums\TimeSlotStatus;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\SelectColumn;
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
                SelectColumn::make('status')
                    ->options(AppointmentStatus::options()),
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
            ->actions([
                //
            ]);
    }
}
