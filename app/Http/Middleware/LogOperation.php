<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\System\Log as LogModel;

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
        $data = $request->input();
        if (isset($data['_t'])) {
            unset($data['_t']);
        }
        $log = [
            'user_id' => Auth::id(),
            'path' => substr($request->path(), 0, 255),
            'action' => $request->method(),
            'ip' => $request->getClientIp(),
            'data' => $data,
        ];

        try {
            LogModel::create($log);
        } catch (\Exception $exception) {
            // pass
        }
        return $next($request);
    }
}
