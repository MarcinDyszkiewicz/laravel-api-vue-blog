<?php

namespace App\Http\Controllers;

use App\Helpers\UserControllerHelper;
use App\Models\User;
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
        //@todo walidacja role. moga byc tylko okreslone wartosci.
        try {
            if($request->role) {
                $role = $this->userControllerHelper->changeRole($user, $request);
            }

            return response()->json(['data' => $user, 'message' => 'user has been updated', 'success' => true]);
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
