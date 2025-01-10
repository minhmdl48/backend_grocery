<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RestrictIPMiddleware
{
    /**
     * Danh sách IP được phép truy cập.
     */
    protected $allowed_ips = [
        '127.0.0.1', // Localhost
        '192.168.1.100', // Ví dụ IP
    ];

    /**
     * Xử lý middleware.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!in_array($request->ip(), $this->allowed_ips)) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        return $next($request);
    }
}
