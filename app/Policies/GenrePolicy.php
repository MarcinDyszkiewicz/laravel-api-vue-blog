<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Genre;
use Illuminate\Auth\Access\HandlesAuthorization;

class GenrePolicy
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
     * Determine whether the user can create genres.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function manage(User $user)
    {
        return $user->hasPermission('Create And Manage Categories');
    }
}