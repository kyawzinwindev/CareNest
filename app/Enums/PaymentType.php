<?php

namespace App\Enums;

enum PaymentType: string
{
    case ONLINE = "online";
    case ONSITE = "onsite";

    public function label(): string
    {
        return match ($this) {
            self::ONLINE => 'Online',
            self::ONSITE => 'Onsite',
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
