<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\User;

class AssignRolePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

     public function assignRole(User $user, Role $role)
    {
        return match ($user->role) {
            Role::ROOT => in_array($role, [Role::ADMIN, Role::DOCTOR, Role::PATIENT]),
            Role::ADMIN => in_array($role, [Role::DOCTOR, Role::PATIENT]),
            Role::DOCTOR => $role === Role::PATIENT,
            default => false
        };
    }
}
