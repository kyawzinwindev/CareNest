<?php

namespace App\Filament\Resources\Schedules\Pages;

use App\Filament\Resources\Schedules\ScheduleResource;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditSchedule extends EditRecord
{
    protected static string $resource = ScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function beforeSave(): void
    {
        $hasAppointments = $this->record
            ->time_slots()
            ->whereHas('appointment')
            ->exists();

        if ($hasAppointments) {
            Notification::make()
                ->title('Schedule cannot be updated')
                ->body('Some time slots already have appointments.')
                ->danger()
                ->send();

            $this->halt();
        }
    }
}
