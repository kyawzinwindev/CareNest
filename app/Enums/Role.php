<?php

namespace App\Enums;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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
        $user = Auth::user();

        return collect(self::cases())
            ->filter(fn($role) => Gate::forUser($user)->allows('assignRole',[User::class,$role]))
            ->mapWithKeys(fn($case) => [
                $case->value => $case->label(),
            ])
            ->toArray();
    }
}
