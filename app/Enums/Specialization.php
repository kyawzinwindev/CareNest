<?php

namespace App\Enums;

enum Specialization: string
{
    case CARDIOLOGY = 'cardiology';
    case DERMATOLOGY = 'dermatology';
    case PEDIATRICS = 'pediatrics';
    case GENERAL_MEDICINE = 'general_medicine';
    case NEUROLOGY = 'neurology';
    case ORTHOPEDICS = 'orthopedics';
    case GYNECOLOGY = 'gynecology';
    case OPHTHALMOLOGY = 'ophthalmology';

    public function label(): string
    {
        return match ($this) {
            self::CARDIOLOGY => 'Cardiology',
            self::DERMATOLOGY => 'Dermatology',
            self::PEDIATRICS => 'Pediatrics',
            self::GENERAL_MEDICINE => 'General Medicine',
            self::NEUROLOGY => 'Neurology',
            self::ORTHOPEDICS => 'Orthopedics',
            self::GYNECOLOGY => 'Gynecology',
            self::OPHTHALMOLOGY => 'Ophthalmology',
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
