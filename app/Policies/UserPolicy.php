<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\User;

class UserPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->role === Role::ROOT) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === Role::ADMIN;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return $user->role === Role::ADMIN || $model->id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === Role::ADMIN;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        if ($user->role === Role::ADMIN) {
            return $model->role !== Role::ROOT || $model->id === $user->id;
        }

        return $model->id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        if ($user->role === Role::ADMIN) {
            return $model->role !== Role::ROOT || $model->id === $user->id;
        }

        return $model->id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        if ($user->role === Role::ADMIN) {
            return $model->role !== Role::ROOT || $model->id === $user->id;
        }

        return $model->id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        if ($user->role === Role::ADMIN) {
            return $model->role !== Role::ROOT || $model->id === $user->id;
        }

        return $model->id === $user->id;
    }

    /**
     * Determine whether the user can assign the role to created user.
     */
    public function assignRole(User $user, Role $role): bool
    {
        if ($user->role === Role::ADMIN) {
            return $role !== Role::ROOT;
        }

        return false;
    }
}

