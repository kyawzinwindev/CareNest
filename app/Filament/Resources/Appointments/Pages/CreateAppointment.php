<?php

namespace App\Filament\Resources\Appointments\Pages;

use App\Enums\AppointmentStatus;
use App\Enums\PaymentType;
use App\Enums\Role;
use App\Enums\TimeSlotStatus;
use App\Filament\Resources\Appointments\AppointmentResource;
use Filament\Resources\Pages\CreateRecord;
use Override;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;

    #[Override]
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();

        if ($user->role === Role::DOCTOR) {
            $data['doctor_id'] = $user->doctor->id;
        }

        $data['status'] = AppointmentStatus::CONFIRMED;
        $data['payment_type'] = PaymentType::ONSITE;

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->time_slot->update([
            'status' => TimeSlotStatus::BOOKED
        ]);
    }
}
