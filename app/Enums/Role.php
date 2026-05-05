<?php

namespace App\Enums;

enum Role: string
{
    case ROOT = 'root';
    case ADMIN = 'admin';
    case DOCTOR = 'doctor';
    case PATIENT = 'patient';

    public function label(): string
    {
        return match ($this) {
            self::ROOT => 'Root',
            self::ADMIN => 'Admin',
            self::DOCTOR => 'Doctor',
            self::PATIENT => 'Patient'
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [
                $case->value => ucfirst($case->value),
            ])
            ->toArray();
    }
}
