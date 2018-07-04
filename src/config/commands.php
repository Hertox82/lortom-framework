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
            return new \LTFramework\Commands\AddTempPlugin($app['lt-templ.plugin.compiler']);
        }
    ],
    [
        'method'      => 'bind',
        'abstract'    => 'lt-template::create',
        'closure'     => function($app){
            return new \LTFramework\Commands\CreateTemplate($app['lt-templ.create.compiler']);
        }
    ],
    [
        'method'      => 'bind',
        'abstract'    => 'lt-template::delete',
        'closure'     => function($app){
            return new \LTFramework\Commands\DeleteTemplate($app['lt-templ.delete.compiler']);
        }
    ],
    [
        'method'      => 'bind',
        'abstract'    => 'lt-template::rm-plugin',
        'closure'     => function($app){
            return new \LTFramework\Commands\RemTempPlugin($app['lt-templ.plugin.compiler']);
        }
    ],
    [
        'method'      => 'bind',
        'abstract'    => 'lt-plugin::create',
        'closure'     => function($app){
            return new \LTFramework\Commands\CreatePlugin($app['lt-plugin.create.compiler']);
        }
    ],
    [
        'method'      => 'bind',
        'abstract'    => 'lt-plugin::delete',
        'closure'     => function($app){
            return new \LTFramework\Commands\DeletePlugin($app['lt-plugin.delete.compiler']);
        }
    ],
    [
        'method'      => 'bind',
        'abstract'    => 'lt-plugin::update',
        'closure'     => function($app){
            return new \LTFramework\Commands\UpdatePlugin($app['lt-plugin.update.compiler']);
        }
    ],
    [
        'method'      => 'bind',
        'abstract'    => 'lt-routing::enable',
        'closure'     => function($app){
            return new \LTFramework\Commands\EnableRouting($app['lt-plugin.routing.compiler']);
        }
    ],
    [
        'method'      => 'bind',
        'abstract'    => 'lt-template::dbexp',
        'closure'     => function($app){
            return new \LTFramework\Commands\ExportTemplateDatabase();
        }
    ],
    [
        'method'      => 'bind',
        'abstract'    => 'lt-migration::up',
        'closure'     => function($app){
            return new \LTFramework\Commands\UpdateMigrationUp($app['lt-plugin.update.compiler']);
        }
    ],
    [
        'method'      => 'bind',
        'abstract'    => 'lt-migration::down',
        'closure'     => function($app){
            return new \LTFramework\Commands\UpdateMigrationDown($app['lt-plugin.update.compiler']);
        }
    ],
    [
        'method'      => 'bind',
        'abstract'    => 'lt-permission::update',
        'closure'     => function($app){
            return new \LTFramework\Commands\UpdatePermission($app['lt-plugin.update.compiler']);
        }
    ]
];