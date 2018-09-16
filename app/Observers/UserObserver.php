<?php

namespace App\Observers;

use App\User;

class UserObserver
{
    /**
     * Handle to the User "creating" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function creating(User $user)
    {
        //
    }
}