<?php
/**
 * User: hernan
 * Date: 04/07/2018
 * Time: 10:23
 */


return [
    [
        'method'      => 'bind',
        'abstract'    => 'plugin.config.compiler',
        'closure'     => function($app){
            return new \LTFramework\Services\PluginsConfigCompiler();
        }
    ],
    [
        'method'      => 'singleton',
        'abstract'    => 'lt-plugin.routing.compiler',
        'closure'     => function($app){
            return new \LTFramework\Services\PluginRoutingCompiler();
        }
    ],
    [
        'method'      => 'singleton',
        'abstract'    => 'lt-plugin.create.compiler',
        'closure'     => function($app){
            return new \LTFramework\Services\PluginCreateCompiler($app['plugin.config.compiler']);
        }
    ],
    [
        'method'      => 'singleton',
        'abstract'    => 'lt-plugin.delete.compiler',
        'closure'     => function($app){
            return new \LTFramework\Services\PluginDeleteCompiler($app['plugin.config.compiler']);
        }
    ],
    [
        'method'      => 'singleton',
        'abstract'    => 'lt-plugin.update.compiler',
        'closure'     => function($app){
            return new \LTFramework\Services\PluginUpdateCompiler($app['plugin.config.compiler']);
        }
    ],
    [
        'method'      => 'singleton',
        'abstract'    => 'lt.user.provider',
        'closure'     => function($app){
            return new \LTFramework\Services\Classes\LortomUserProvider();
        }
    ],
    [
        'method'      => 'singleton',
        'abstract'    => 'ltAuth',
        'closure'     => function($app){
            return new \LTFramework\Services\Classes\LortomAuth($this->app['lt.user.provider']);
        }
    ],
    [
        'method'      => 'singleton',
        'abstract'    => 'ltpm',
        'closure'     => function($app){
            return new \LTFramework\Services\Classes\PackageManager();
        }
    ],
    [
        'method'      => 'singleton',
        'abstract'    => 'lt.seeder',
        'closure'     => function($app){
            return new \LTFramework\Services\RepoSeeder();
        }
    ],
    [
        'method'      => 'bind',
        'abstract'    => 'App\Services\LortomSeeder',
        'closure'     => function($app){
            return new \LTFramework\Services\LortomSeeder($app['lt.seeder']);
        }
    ],
];