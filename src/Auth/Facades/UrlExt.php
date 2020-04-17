<?php 

namespace LTFramework\Auth\Facades;

use Illuminate\Support\Facades\Facade;

class UrlExt extends Facade {

    protected static function getFacadeAccessor()
    {
        return 'lt.urlext';
    }
}