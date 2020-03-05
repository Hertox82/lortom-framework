<?php
/**
 * User: hernan
 * Date: 23/10/2017
 * Time: 10:57
 */

namespace LTFramework\Middleware;

use Closure;
use Illuminate\Http\Request;
use LTFramework\Services\Facades\LtAuth;

class LortomAuthentication
{

    public function handle(Request $request, Closure $next)
    {
        // Controllare se nell'header c'è il token JWT
        $currentPath = $request->path();

        if($currentPath === 'backend/login'){

            $response = $next($request);
            // $response->headers->set('X-FRAME-OPTIONS','DENY');
            return $response;
        }
        // Verificare prima se il token è in sessione

        $token = LtAuth::getToken();

        if(strlen($token) == 0)
        {
            return redirect('backend/login')
            ->withCookie(cookie('l_at','',-1))
            ->withCookie(cookie('l_bt','',-1));
            
        }
        else
        {
            //autentica il token
            if($check = LtAuth::validateToken($token))
            {
                $user = LtAuth::getUser();
                $request->merge(['user' => $user]);
                $request->setUserResolver(function() use ($user){
                    return $user;
                });
                return $response = $next($request);
            }
            else if($check === false)
            {
                $token = LtAuth::refreshToken();
                $user = LtAuth::getUser();
                $request->merge(['user' => $user]);
                $request->setUserResolver(function() use ($user){
                    return $user;
                });
                $response = $next($request);

                list($cookie1,$cookie2) = LtAuth::makeCookies(LtAuth::splitToken($token));
                return $response->withCookie($cookie1)
                                ->withCookie($cookie2); 
            }
            else
            {
                unset($_COOKIE['l_at']);
                unset($_COOKIE['l_bt']);
                
                return response()->json(['error' => 'not Authorized'],401) //redirect('backend/login')
                ->withCookie(cookie('l_at','',-1))
                ->withCookie(cookie('l_bt','',-1));
            }
        }

    }
}