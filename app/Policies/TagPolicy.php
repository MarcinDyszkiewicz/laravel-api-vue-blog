<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Tag;
use Illuminate\Auth\Access\HandlesAuthorization;

class TagPolicy
{
    use HandlesAuthorization;

    /**
     * @param $user
     * @return bool
     */
    public function before(User $user)
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can manage tags.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function manage(User $user)
    {
        return $user->hasPermission('Manage Tags');
    }
}
