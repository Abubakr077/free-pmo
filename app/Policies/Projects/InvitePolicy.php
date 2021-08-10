<?php

namespace App\Policies\Projects;

use App\Entities\Projects\Invite;
use App\Entities\Users\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvitePolicy
{
    use HandlesAuthorization;

    public function create(User $user, Invite $invite)
    {
        return $user->hasRole('admin')
            || $user->hasRole('worker')
            || $user->hasRole('supervisor');
    }

    /**
     * Determine whether the user can add comment to an Invite.
     *
     * @param  \App\Entities\Users\User  $user
     * @param  \App\Entities\Projects\Invite  $invite
     * @return bool
     */
    public function commentOn(User $user, Invite $invite)
    {
        return true;
    }
}
