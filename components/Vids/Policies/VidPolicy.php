<?php

namespace Components\Vids\Policies;

use Components\Users\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class VidPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    public function aprove(User $user)
    {
        return $user->role->can_aprove;
    }

    public function delete(User $user)
    {
        return $user->role->can_delete;
    }
}
