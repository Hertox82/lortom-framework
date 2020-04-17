<?php

namespace LTFramework\Auth;

use Illuminate\Contracts\Routing\UrlRoutable;
use URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\InteractsWithTime;
use Illuminate\Support\Facades\Route;

class UrlExtension 
{

    use InteractsWithTime;

    protected $keyResolver;

    /**
     * This function register the Route
     * 
     * @return void
     */
    public function bootRoute($namespace) 
    {
        Route::middleware('web')
        ->namespace($namespace)
        ->group($this->registerRoute());    
    }

    protected function registerRoute() {
        return function ($route) {
            Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
            Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify');
            Route::get('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');
        };
    }

    /**
     * Create a temporary signed route URL for a named route.
     *
     * @param  string  $name
     * @param  \DateTimeInterface|\DateInterval|int  $expiration
     * @param  array  $parameters
     * @param  bool  $absolute
     * @return string
     */
    public function temporarySignedRoute($name, $expiration, $parameters = [], $absolute = true) {
        return $this->signedRoute($name, $parameters, $expiration, $absolute);
    }


    /**
     * Create a signed route URL for a named route.
     *
     * @param  string  $name
     * @param  array  $parameters
     * @param  \DateTimeInterface|\DateInterval|int|null  $expiration
     * @param  bool  $absolute
     * @return string
     */
    public function signedRoute($name, $parameters = [], $expiration = null, $absolute = true)
    {
        $parameters = $this->formatParameters($parameters);

        if ($expiration) {
            $parameters = $parameters + ['expires' => $this->availableAt($expiration)];
        }

        ksort($parameters);

        $key = call_user_func($this->keyResolver);

        return URL::route($name, $parameters + [
            'signature' => hash_hmac('sha256', URL::route($name, $parameters, $absolute), $key),
        ], $absolute);
    }

    /**
     * Determine if the given request has a valid signature.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  bool  $absolute
     * @return bool
     */
    public function hasValidSignature(Request $request, $absolute = true)
    {
        $url = $absolute ? $request->url() : '/'.$request->path();

        $original = rtrim($url.'?'.Arr::query(
            Arr::except($request->query(), 'signature')
        ), '?');

        $expires = $request->query('expires');

        $signature = hash_hmac('sha256', $original, call_user_func($this->keyResolver));

        return hash_equals($signature, (string) $request->query('signature', '')) &&
               ! ($expires && Carbon::now()->getTimestamp() > $expires);
    }

    /**
     * Set the encryption key resolver.
     *
     * @param  callable  $keyResolver
     * @return $this
     */
    public function setKeyResolver(callable $keyResolver) {
        $this->keyResolver = $keyResolver;

        return $this;
    }

     /**
     * Format the array of URL parameters.
     *
     * @param  mixed|array  $parameters
     * @return array
     */
    public function formatParameters($parameters)
    {
        $parameters = Arr::wrap($parameters);

        foreach ($parameters as $key => $parameter) {
            if ($parameter instanceof UrlRoutable) {
                $parameters[$key] = $parameter->getRouteKey();
            }
        }

        return $parameters;
    }

}