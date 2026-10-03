<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function before(User $user): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $currentUser, User $user): bool
    {
        return $currentUser->isAdmin() || $currentUser->id === $user->id;
    }

    public function update(User $currentUser, User $user): bool
    {
        return $currentUser->id === $user->id;
    }
}
