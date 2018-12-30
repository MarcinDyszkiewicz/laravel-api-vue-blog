<?php

namespace App\Http\Controllers;

use App\Helpers\UserControllerHelper;
use App\Models\User;
use App\Rules\roles;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userControllerHelper;

    public function __construct(UserControllerHelper $userControllerHelper)
    {
        $this->userControllerHelper = $userControllerHelper;
    }

    /**
     * Updates user's own profile
     *
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, User $user)
    {
        $user = $this->userControllerHelper->updateProfile($user, $request);

        return response()->json(['data' => $user, 'message' => 'user has been updated', 'success' => true]);
    }

    /**
     * Updates user's roles
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required', 'int', new roles],
        ]);

        try {
            $user = $this->userControllerHelper->changeRole($user, $request->role);

            return response()->json(['data' => $user, 'message' => 'User\'s role has been updated to '.User::$roleMap[$user->role], 'success' => true]);
        } catch (\Exception $e) {
            return response()->json(['data' => null, 'message' => $e, 'success' => false]);
        }
    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePermission(Request $request, User $user)
    {
        $request->validate([
           'permissionIds' => 'nullable|array|exists:permissions,id'
        ]);

        try{
            $user = $this->userControllerHelper->changePermission($user, $request->permissionIds);

            return response()->json(['data' => $user, 'message' => 'User\'s permissions has been updated to '.$user->permissions()->pluck('name')->implode(', '), 'success' => true]);
        } catch (\Exception $e) {
            return response()->json(['data' => null, 'message' => $e, 'success' => false]);
        }
    }
}
