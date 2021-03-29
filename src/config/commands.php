<?php
/**
 * User: hernan
 * Date: 04/07/2018
 * Time: 10:03
 */


return [
    [
        'method'      => 'bind',
        'abstract'    => 'lt-template::add-plugin',
        'closure'     => function($app){
            return new \LTFramework\Template\Console\AddTempPlugin($app['lt-templ.plugin.compiler']);
        }
    ],
    [
        'method'      => 'bind',
        'abstract'    => 'lt-template::create',
        'closure'     => function($app){
            return new \LTFramework\Template\Console\CreateTemplate($app['lt-templ.create.compiler']);
        }
    ],
    [
        'method'      => 'bind',
        'abstract'    => 'lt-template::delete',
        'closure'     => function($app){
            return new \LTFramework\Template\Console\DeleteTemplate($app['lt-templ.delete.compiler']);
        }
    ],
    [
        'method'      => 'bind',
        'abstract'    => 'lt-template::rm-plugin',
        'closure'     => function($app){
            return new \LTFramework\Template\Console\RemTempPlugin($app['lt-templ.plugin.compiler']);
        }
    ],
    [
        'method'      => 'bind',
        'abstract'    => 'lt-plugin::create',
        'closure'     => function($app){
            return new \LTFramework\Plugins\Console\CreatePlugin($app['lt-plugin.create.compiler']);
        }
    ],
    [
        'method'      => 'bind',
        'abstract'    => 'lt-plugin::delete',
        'closure'     => function($app){
            return new \LTFramework\Plugins\Console\DeletePlugin($app['lt-plugin.delete.compiler']);
        }
    ],
    [
        'method'      => 'bind',
        'abstract'    => 'lt-plugin::update',
        'closure'     => function($app){
            return new \LTFramework\Plugins\Console\UpdatePlugin($app['lt-plugin.update.compiler']);
        }
    ],
    [
        'method'      => 'bind',
        'abstract'    => 'lt-routing::enable',
        'closure'     => function($app){
            return new \LTFramework\Plugins\Console\EnableRouting($app['lt-plugin.routing.compiler']);
        }
    ],
    [
        'method'      => 'bind',
        'abstract'    => 'lt-template::dbexp',
        'closure'     => function($app){
            return new \LTFramework\Template\Console\ExportTemplateDatabase();
        }
    ],
    [
        'method'      => 'bind',
        'abstract'    => 'lt-migration::up',
        'closure'     => function($app){
            return new \LTFramework\Plugins\Console\UpdateMigrationUp($app['lt-plugin.update.compiler']);
        }
    ],
    [
        'method'      => 'bind',
        'abstract'    => 'lt-migration::down',
        'closure'     => function($app){
            return new \LTFramework\Plugins\Console\UpdateMigrationDown($app['lt-plugin.update.compiler']);
        }
    ],
    [
        'method'      => 'bind',
        'abstract'    => 'lt-permission::update',
        'closure'     => function($app){
            return new \LTFramework\Plugins\Console\UpdatePermission($app['lt-plugin.update.compiler']);
        }
    ],
    [
        'method'      => 'bind',
        'abstract'    => 'lt-setup::init',
        'closure'     => function($app){
            return new \LTFramework\Commands\Setup($app['lt-setup.compiler']);
        }
    ],
    [
        'method'      => 'bind',
        'abstract'    => 'lt-gitignore::delete',
        'closure'     => function($app){
            return new \LTFramework\Commands\DeleteGitignore();
        }
    ],
    [
        'method'      => 'bind',
        'abstract'    => 'lt-route::cache',
        'closure'     => function($app){
            return new \LTFramework\Commands\CacheRoute();
        }
    ],
    [
        'method'      => 'bind',
        'abstract'    => 'lt-route::refresh-cache',
        'closure'     => function($app){
            return new \LTFramework\Commands\RefreshCacheRoute();
        }
    ]
];