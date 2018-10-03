<?php

namespace App\Helpers;

use App\Models\User;

class UserControllerHelper
{

    /**
     * Updating user's own profile
     *
     * @param User $user
     * @param $data
     * @return User
     */
    public function updateProfile(User $user, $data): User
    {
        $user->name = $data->name;
        $user->password = bcrypt($data->new_password);
        $user->save();

        return $user;
    }
}
