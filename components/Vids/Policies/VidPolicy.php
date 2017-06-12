<?php

namespace Components\Vids\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use Components\Users\Models\User;
use Components\Vids\Models\Vid;

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

    public function vote(User $user, Vid $vid)
    {
        return !($user->vids_votes()->where('vid_id', $vid->id)->count());
    }

    public function check(User $user)
    {
        return ($user->role->id == 1 or $user->role->id == 2);
    }
}
