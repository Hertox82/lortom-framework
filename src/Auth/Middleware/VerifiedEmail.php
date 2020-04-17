<?php

namespace LTFramework\Auth\Middleware;

use Closure;
use Illuminate\Http\Request;
use LTFramework\Contracts\UserMustVerifyEmail;
use Illuminate\Support\Facades\Redirect;

class VerifiedEmail {

    public function handle(Request $request, Closure $next) 
    {
        if(! $request->user()) 
            return $next($request);

        $currentPath = $request->path();

        if($currentPath === 'logout') 
            return $next($request);
            
        if($request->user() instanceof UserMustVerifyEmail && ! $request->user()->hasEmailVerified()) 
        {
            return Redirect::route('verification.notice');
        }

        return $next($request);
    }
}