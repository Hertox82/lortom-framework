<?php
/**
 * User: hernan
 * Date: 04/07/2018
 * Time: 10:08
 */


return [
    [
        'method'      => 'singleton',
        'abstract'    => 'lt-templ.plugin.compiler',
        'closure'     => function($app){
            return new \LTFramework\Template\Compiler\TemplatePlugCompiler($app['plugin.config.compiler']);
        }
    ],
    [
        'method'      => 'singleton',
        'abstract'    => 'lt-templ.create.compiler',
        'closure'     => function($app){
            return new \LTFramework\Template\Compiler\TemplateCreateCompiler();
        }
    ],
    [
        'method'      => 'singleton',
        'abstract'    => 'lt-templ.delete.compiler',
        'closure'     => function($app){
            return new \LTFramework\Template\Compiler\TemplateDeleteCompiler();
        }
    ],
];
