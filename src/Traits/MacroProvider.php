<?php

namespace LTFramework\Traits;

use Illuminate\Support\Facades\URL;
use LTFramework\Auth\Facades\UrlExt;
use Illuminate\Http\Request;

trait MacroProvider 
{
     /**
     * This function register URL macro used for create temporary signed route
     * 
     * @return void
     */
    public function registerUrlMacro() {

        // URL::signedRoute , this function create a signed Route
        URL::macro('signedRoute',function($name, $parameters = [], $expiration = null, $absolute = true){
            return UrlExt::signedRoute($name, $parameters,$expiration, $absolute);
        });
        // URL::temporarySignedRoute, this function create a temporary signed route
        URL::macro('temporarySignedRoute',function($name, $expiration, $parameters = [], $absolute = true){
            return UrlExt::temporarySignedRoute($name, $expiration, $parameters, $absolute);
        });
        // URL::hasValidSignature, this function check if route have a valid signature
        URL::macro('hasValidSignature',function(Request $request, $absolute= true){
            return UrlExt::hasValidSignature($request, $absolute);
        });   
    }

    /**
     * This function register Request Macro
     */
    public function registerRequestMacro() {
        Request::macro('hasValidSignature',function($absolute = true){
            return URL::hasValidSignature($this,$absolute);
        });
    }
}