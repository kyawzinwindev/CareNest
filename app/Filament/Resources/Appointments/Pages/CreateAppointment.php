<?php

namespace App\Filament\Resources\Appointments\Pages;

use App\Enums\AppointmentStatus;
use App\Enums\PaymentType;
use App\Enums\Role;
use App\Enums\TimeSlotStatus;
use App\Filament\Resources\Appointments\AppointmentResource;
use App\Models\TimeSlot;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\DB;
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

    protected function beforeCreate(): void
    {
        $timeSlotId = $this->data['time_slot_id'] ?? null;

        if (! $timeSlotId) {
            return;
        }

        DB::transaction(function () use ($timeSlotId) {
            $timeSlot = TimeSlot::where('id', $timeSlotId)
                ->lockForUpdate()
                ->first();

            if (! $timeSlot || $timeSlot->status !== TimeSlotStatus::AVAILABLE) {
                Notification::make()
                    ->title('Time slot already booked')
                    ->body('This time slot has just been booked by another user. Please select a different time slot.')
                    ->danger()
                    ->persistent()
                    ->send();

                throw new Halt();
            }
        });
    }

    protected function afterCreate(): void
    {
        $this->record->time_slot->update([
            'status' => TimeSlotStatus::BOOKED
        ]);
    }
}
