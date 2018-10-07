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
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, User $user)
    {
        $user = $this->userControllerHelper->updateProfile($user, $request);

        return response()->json(['data' => $user, 'message' => 'user has been updated', 'success' => true]);
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required', 'int', new roles],
        ]);

        try {
            $this->userControllerHelper->changeRole($user, $request->role);

            return response()->json(['data' => $user, 'message' => 'User\'s role has been updated to '.User::$roleMap[$request->role], 'success' => true]);
        } catch (\Exception $e) {
            return response()->json(['data' => null, 'message' => $e, 'success' => false]);
        }
    }

    public function updatePermission(Request $request, User $user)
    {
        try{
            $this->userControllerHelper->changePermission($user, $request);

            return response()->json(['data' => $user, 'message' => 'user has been updated', 'success' => true]);
        } catch (\Exception $e) {
            return response()->json(['data' => null, 'message' => $e, 'success' => false]);
        }
    }

}
