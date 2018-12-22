<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
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
     * Determine whether the user can update the comment.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Comment  $comment
     * @return mixed
     */
    public function manage(User $user, Comment $comment)
    {
        return $comment->user_id == $user->id || $user->hasPermission('Manage All Comments');
    }
}
