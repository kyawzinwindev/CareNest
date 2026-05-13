<?php

namespace App\Enums;

enum TimeSlotStatus: string
{
    case AVAILABLE = 'available';
    case UNAVAILABLE = 'unavailable';
    case BOOKED = 'booked';

    public function label(): string
    {
        return match ($this) {
            self::AVAILABLE => 'Available',
            self::UNAVAILABLE => 'Unavailable',
            self::BOOKED => 'Booked',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [
                $case->value => $case->label(),
            ])
            ->toArray();
    }
}
