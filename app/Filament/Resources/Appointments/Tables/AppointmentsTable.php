<?php

namespace App\Filament\Resources\Appointments\Tables;

use App\Enums\AppointmentStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
                TextColumn::make("schedule.date")
                    ->label("Schedule Date"),
                TextColumn::make("service.name")
                    ->label("Service"),
                TextColumn::make("start_time")->label("Start Time"),
                TextColumn::make("end_time")->label("End Time"),
                TextColumn::make('payment_type')
                    ->label("Payment Type")
                    ->badge(),
                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => AppointmentStatus::PENDING->value,
                        'info' => AppointmentStatus::CONFIRMED->value,
                        'danger' => AppointmentStatus::CANCELLED->value,
                        'success' => AppointmentStatus::COMPLETED->value,
                    ]),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
