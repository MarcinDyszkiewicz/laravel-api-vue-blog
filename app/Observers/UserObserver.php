<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle to the User "creating" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function creating(User $user)
    {
//        $user->api_token = bin2hex(openssl_random_pseudo_bytes(30));
    }
}