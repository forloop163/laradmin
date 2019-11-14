<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Business\System\User as UserBusiness;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function userInfo(Request $request)
    {
        $business = new UserBusiness;
        return $this->success($business->LoginUserInfo(Auth::user()));
    }
}
