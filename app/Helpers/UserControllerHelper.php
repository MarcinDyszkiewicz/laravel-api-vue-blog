<?php

namespace App\Helpers;

use App\Models\Permission;
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

        switch ($user->role){
            case User::ROLE_USER:
                $user->permissions()->detach();
                break;
            case User::ROLE_AUTHOR:
                $permissionId = Permission::getPermissionId(Permission::PERMISSIONS_FOR_AUTHOR);
                $user->permissions()->sync([$permissionId]);
                break;
            case User::ROLE_EDITOR:
                $permissionIds = Permission::getPermissionIds(Permission::PERMISSIONS_FOR_EDITOR);
                $user->permissions()->sync($permissionIds);
                break;
            case User::ROLE_ADMIN:
                $permissionIds = Permission::getPermissionIds(Permission::PERMISSIONS_FOR_ADMIN);
                $user->permissions()->sync($permissionIds);
                break;
        }

        return $user;
    }

    public function changePermission(User $user, $data)
    {
        $user->permissions()->sync($data->permission);

        return $user;
    }

}
