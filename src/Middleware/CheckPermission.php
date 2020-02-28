<?php

namespace LTFramework\Middleware;

use Closure;

class CheckPermission 
{
    /**
     * Handle the incoming request
     * 
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $permission
     * @return mixed
     */
    public function handle($request, Closure $next, $permission) {
        if (! $request->user()->hasPermission($permission)) {
            return response()->json(['error' => 'Access Denied'],403);
        }

        return $next($request);
    }
}