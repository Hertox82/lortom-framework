<?php


namespace LTFramework\Auth\Middleware;

use Closure;
use Illuminate\Http\Request;

class Signed {


    /**
     * 
     */
    public function handle(Request $request, Closure $next) {
        if($request->hasValidSignature()) {
            return $next($request);
        }

        return abort(403,'Invalid signature.');
    }
}