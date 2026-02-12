<?php

namespace App\Policies;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MeetingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function update(User $user, Meeting $meeting)
    {
        return $user->role === 'notulis'
            && $user->id === $meeting->created_by;
    }

    public function create(User $user)
    {
        return $user->isAdmin() || $user->isNotulis();
    }

    public function view(User $user)
    {
        return in_array($user->role, ['admin', 'notulis', 'viewer']);
    }



    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Meeting $meeting): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Meeting $meeting): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Meeting $meeting): bool
    {
        return false;
    }
}
