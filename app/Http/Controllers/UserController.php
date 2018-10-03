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

        return response()->json(['message' => 'user has been updated', 'data' => $user]);
    }

    public function updateRoleAndPermission(Request $request, User $user)
    {
        if($request->role) {
            $user = $this->userControllerHelper->changeRole($user, $request);
        }

        if($request->permission) {
            $user = $this->userControllerHelper->changePermission($user, $request);
        }


    }
    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ];
    }

    protected function validationErrorMessages()
    {
        return [];
    }
}
