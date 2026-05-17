<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Thread;

class ThreadPolicy
{
    public function update(User $user, Thread $thread): bool
    {
        return $user->id === $thread->user_id || $user->is_admin;
    }

    public function delete(User $user, Thread $thread): bool
    {
        return $user->id === $thread->user_id || $user->is_admin;
    }
}
