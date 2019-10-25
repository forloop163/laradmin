<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponseHelper;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    use ApiResponseHelper;

    protected function unauthenticated($request, array $guards)
    {
        abort(410, '您登录已过期，请重新登录。');
    }
}
