<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
//        if (!$request->expectsJson()) {
//            return route('login');
//        }


//        $routeName = app('router')->currentRouteName();
//
//        // 如果未登录
//        $refererHost = $request->header('referer');
//        $refererHost = rtrim($refererHost, '/');
//
//        $appUrl = config('url');
//
//        if (Auth::guard($guard)->guest()) {
//            if ($request->expectsJson()) {
//                return response()->json([
//                    'code' => 401,
//                    'msg' => '您登录已过期，请重新登录。',
//                    'redirect' => '/',
//                ], 401);
//            } else {
//                return redirect('/');
//            }
//        }


        return $next($request);
    }
}
