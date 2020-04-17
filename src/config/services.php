<?php

return [
    [
        'method'      => 'singleton',
        'abstract'    => 'lt.user.provider',
        'closure'     => function($app){
            return new \LTFramework\Auth\LortomUserProvider();
        }
    ],
    [
        'method'      => 'singleton',
        'abstract'    => 'ltAuth',
        'closure'     => function($app){
            return new \LTFramework\Auth\LortomAuth($this->app['lt.user.provider']);
        }
    ],
    [
        'method'      => 'singleton',
        'abstract'    => 'lt-multisitemanager',
        'closure'     => function($app){
            return new \LTFramework\Services\Classes\MultiSiteManager();
        }
    ],
];