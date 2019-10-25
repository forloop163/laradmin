<?php

namespace App\Http\Middleware;

use App\Business\System\Auth as AuthBusiness;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;

class LaradminMiddleware
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     * @throws AuthenticationException
     */
    public function handle($request, Closure $next)
    {
        $routeName = Route::currentRouteName();
        $authBusiness = new AuthBusiness;

        $user = $authBusiness->authUser();

        if (Gate::denies($routeName)) {
            throw new AuthenticationException('您的权限不足！');
        }
        return $next($request);
    }
}
