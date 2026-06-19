<?php

namespace App\Filament\Widgets;

use App\Enums\Role;
use App\Models\Appointment;
use App\Filament\Resources\Appointments\AppointmentResource;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TodayAppointmentsWidget extends BaseWidget
{
    protected static ?string $heading = "Today's Appointments";
    
    protected static ?int $sort = 2;
    
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $user = auth()->user();

        $query = Appointment::query()
            ->whereHas('time_slot.schedule', fn($q) => $q->where('date', now()->format('Y-m-d')));

        if ($user->role === Role::DOCTOR) {
            $query->where('doctor_id', $user->doctor?->id);
        }

        return $table
            ->query($query)
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('id')->label('ID'),
                TextColumn::make('patient.user.name')->label('Patient'),
                TextColumn::make('doctor.user.name')->label('Doctor'),
                TextColumn::make('service.name')->label('Service'),
                TextColumn::make('time_slot')
                    ->label('Time Slot')
                    ->formatStateUsing(fn($record) => $record->time_slot ? $record->time_slot->start_time . ' - ' . $record->time_slot->end_time : ''),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->actions([
                // Action::make('edit')
                //     ->label('View / Edit')
                //     ->url(fn(Appointment $record): string => AppointmentResource::getUrl('edit', ['record' => $record]))
                //     ->icon('heroicon-m-pencil-square'),
            ]);
    }
}
