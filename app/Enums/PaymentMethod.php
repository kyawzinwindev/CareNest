<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case CARD = 'card';
    case QR = 'qr';

    public function label(): string
    {
        return match ($this) {
            self::CARD => 'Card',
            self::QR => 'QR',
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

