<?php

return [
    [
        'method'      => 'singleton',
        'abstract'    => 'lt-multisitemanager',
        'closure'     => function($app){
            return new \LTFramework\Services\Classes\MultiSiteManager();
        }
    ],
];