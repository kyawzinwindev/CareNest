<?php

namespace App\Filament\Resources\Schedules\Pages;

use App\Enums\Role;
use App\Filament\Resources\Schedules\ScheduleResource;
use Filament\Resources\Pages\CreateRecord;
use Override;

class CreateSchedule extends CreateRecord
{
    protected static string $resource = ScheduleResource::class;

    #[Override]
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();

        if ($user->role === Role::DOCTOR) {
            $data['doctor_id'] = $user->doctor->id;
        }

        return $data;
    }
}
