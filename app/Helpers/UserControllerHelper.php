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

    public function changeRole(User $user, $data)
    {
        $user->role = $data->role;
        $user->save();

        if ($data->permission){
            switch ($user->role){
                case User::ROLE_USER:
                    //@todo add permission
                    break;
                case User::ROLE_AUTHOR:
                    //@todo add permission
                    break;
                case User::ROLE_EDITOR:
                    //@todo add permission
                    break;
                case User::ROLE_ADMIN:
                    //@todo add permission
                    break;
            }
        }
        return $user;
    }

    public function changePermission(User $user, $data)
    {
        $user->pemissions->sync($data->permission);

        return $user;
    }

}
