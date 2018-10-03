<?php

namespace App\Http\Controllers;

use App\Helpers\UserControllerHelper;
use App\Models\User;
use Illuminate\Http\Request;
use phpseclib\Crypt\Hash;

class UserController extends Controller
{
    use UserControllerHelper;

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
    public function changePassword(Request $request, User $user)
    {
        $user->name = $request->name;
        $user->password = bcrypt($request->new_password);
        $user->save();

        return response()->json(['message' => 'user has been updated', 'data'=>$user]);
    }

    public function changeRoleAndPermission(User $user, Request $request)
    {
        $user->role = $request->role;
        $user->save();
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
