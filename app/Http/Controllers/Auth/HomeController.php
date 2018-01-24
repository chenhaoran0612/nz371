<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class HomeController extends Controller
{
    use AuthenticatesUsers;

    protected function authenticated(Request $request, $user)
    {
        return ['result' => true];
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        return ['result' => false, 'message' => '账号或密码错误，请重新输入！'];
    }

    protected function sendLockoutResponse(Request $request)
    {
        return ['result' => false, 'message' => '你的登录错误次数过多，请稍后再试！'];
    }

    protected function credentials(Request $request)
    {
        $data = $request->only($this->username(), 'password');
        $data['status'] = 1;
        return $data;
    }
}