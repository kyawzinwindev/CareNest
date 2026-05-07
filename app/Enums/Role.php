<?php

namespace App\Enums;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

enum Role: string
{
    case ROOT = "1";
    case ADMIN = "2";
    case DOCTOR = "3";
    case PATIENT = "4";

    public function label(): string
    {
        return match ($this) {
            self::ROOT => 'Root',
            self::ADMIN => 'Admin',
            self::DOCTOR => 'Doctor',
            self::PATIENT => 'Patient'
        };
    }

    public static function optionsBasedOnUserRole(): array
    {
        $user = Auth::user();

        return collect(self::cases())
            ->filter(fn($role) => Gate::forUser($user)->allows('assignRole', [User::class, $role]))
            ->mapWithKeys(fn($case) => [
                $case->value => $case->label(),
            ])
            ->toArray();
    }

    public static function optionsForFilter(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [
                $case->value => $case->label(),
            ])
            ->toArray();
    }
}
