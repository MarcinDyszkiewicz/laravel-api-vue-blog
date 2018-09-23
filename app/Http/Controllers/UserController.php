<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use phpseclib\Crypt\Hash;

class UserController extends Controller
{
    /**
     * @param User $user
     * @param Request $request
     */
    public function update(User $user, Request $request)
    {
        $this->validate($request, $this->rules(), $this->validationErrorMessages());
        $user->name = $request->name;
        $user->password = Hash::make($request->new_password);
    }

    public function updateBySuperAdmin(User $user, Request $request)
    {

    }

    protected function changeUserRole(User $user, Request $request)
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
