<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * 登陆字段
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * 登陆
     * @param Request $request
     * @return \Illuminate\Http\Response|mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $user = $this->guard()->user();
            if ($user->active == 0) {
                $this->incrementLoginAttempts($request);
                return $this->actived();
            }
            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * 账户冻结
     * @return mixed
     */
    public function actived()
    {
        return $this->failed('您的账户已被冻结');
    }

    /**
     * 通过验证
     *
     * @param  \Illuminate\Http\Request $request
     * @param  mixed $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        $user->last_login = Carbon::now();
        $user->save();

        $userName = $this->username();
        return $this->success(['username' => $user->$userName]);
    }

    /**
     * 没有通过验证
     * @param Request $request
     * @return mixed
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return $this->failed('账号密码错误, 请重新输入');
    }

    /**
     * 登出
     * @param Request $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        return $this->success([]);
    }
}
