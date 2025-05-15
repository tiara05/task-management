<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function viewAny(User $user)
    {
        return in_array($user->role, ['admin', 'manager'])
            ? Response::allow()
            : Response::deny('You do not have permission to view users.');
    }

    public function create(User $user)
    {
        return $user->role === 'admin'
            ? Response::allow()
            : Response::deny('Only admins can create users.');
    }
}

