<?php
/**
 * User: hernan
 * Date: 04/07/2018
 * Time: 10:23
 */


return [
    [
        'method'    => 'bind',
        'abstract'  => '\LTFramework\Contracts\Compiler\ConfigCompiler',
        'closure'   => '\LTFramework\Plugins\Compiler\PluginsConfigCompiler'
    ], 
    [
        'method'      => 'bind',
        'abstract'    => 'plugin.config.compiler',
        'closure'     => function($app){
            return new \LTFramework\Plugins\Compiler\PluginsConfigCompiler();
        }
    ],
    [
        'method'      => 'singleton',
        'abstract'    => 'lt-plugin.routing.compiler',
        'closure'     => function($app){
            return new \LTFramework\Plugins\Compiler\PluginRoutingCompiler();
        }
    ],
    [
        'method'      => 'singleton',
        'abstract'    => 'lt-plugin.create.compiler',
        'closure'     => function($app){
            return new \LTFramework\Plugins\Compiler\PluginCreateCompiler($app['plugin.config.compiler']);
        }
    ],
    [
        'method'      => 'singleton',
        'abstract'    => 'lt-plugin.delete.compiler',
        'closure'     => function($app){
            return new \LTFramework\Plugins\Compiler\PluginDeleteCompiler($app['plugin.config.compiler']);
        }
    ],
    [
        'method'      => 'singleton',
        'abstract'    => 'lt-plugin.update.compiler',
        'closure'     => function($app){
            return new \LTFramework\Plugins\Compiler\PluginUpdateCompiler($app['plugin.config.compiler']);
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
    [
        'method'      => 'singleton',
        'abstract'    => 'lt-setup.compiler',
        'closure'     => function($app){
            return new \LTFramework\Services\SetupCompiler($app['db']);
        }
    ],
];