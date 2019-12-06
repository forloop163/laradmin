<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Business\System\Log as LogBusiness;

class LogOperation
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        if ($request->method() !== 'GET') {
            $data = $request->input();
            if (isset($data['_t'])) {
                unset($data['_t']);
            }

            if (isset($data['password'])) {
                $data['password'] = '******';
            }

            $log = [
                'user_id' => Auth::id(),
                'path' => substr($request->path(), 0, 255),
                'action' => $request->method(),
                'ip' => $request->getClientIp(),
                'data' => $data,
            ];

            LogBusiness::write($log);
        }

        return $next($request);
    }
}
