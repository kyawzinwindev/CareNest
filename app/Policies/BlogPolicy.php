<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Blog;
use App\Models\User;

class BlogPolicy
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
        return in_array($user->role, [Role::ADMIN, Role::DOCTOR]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Blog $blog): bool
    {
        return in_array($user->role, [Role::ADMIN, Role::DOCTOR]);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, [Role::ADMIN, Role::DOCTOR]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Blog $blog): bool
    {
        if ($user->role === Role::ADMIN) {
            return true;
        }

        return $user->role === Role::DOCTOR && $blog->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Blog $blog): bool
    {
        if ($user->role === Role::ADMIN) {
            return true;
        }

        return $user->role === Role::DOCTOR && $blog->user_id === $user->id;
    }
}
