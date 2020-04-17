<?php
/**
 * User: hernan
 * Date: 04/07/2018
 * Time: 09:51
 */

namespace LTFramework;

use Illuminate\Support\ServiceProvider;
use LTFramework\Interfaces\EncapsInterface;
use LTFramework\Auth\UrlExtension;
use LTFramework\Contracts\Notifications\EmailVerification;
use LTFramework\Traits\CommandsProvider;
use LTFramework\Traits\EditorProvider;
use LTFramework\Traits\MacroProvider;
use LTFramework\Traits\RoutingProvider;
use LTFramework\Traits\ServicesProvider as TraitServiceProvider;

class LTFrameworkProvider extends ServiceProvider {

    use TraitServiceProvider, RoutingProvider,
    CommandsProvider, MacroProvider, EditorProvider;

    protected $listOfCommands = [];

    protected $toRegister = [
        'Services',
        'Command',
        'Editor',
        'UrlMacro',
        'RequestMacro',
        'Other',
    ];

    protected $namespace = 'LTFramework\Controllers';

    /**
     * This function boot route made by LTFramework
     * 
     * @return void
     */
    public function boot() {

        $this->bootRouting();

    }

    /**
     * This function register All Service from LTFramework
     * 
     * @return void
     */
    public function register() {

        foreach($this->toRegister as $fn) {
            $this->{"register{$fn}"}();
        }
    }

    /**
     * This function provide to register other Services
     * 
     * @return void
     */
    public function registerOther() 
    {
        $this->app->singleton('lt.urlext', function ($app) {
            $urlext = new UrlExtension();
            // set key resolver
            $urlext->setKeyResolver(function(){
                return $this->app->make('config')->get('app.key'); 
            });

            return $urlext;
        });

        $this->app->bind('email.verification', function($app){
            return $app->make(EmailVerification::class);
        });
        
        $this->app->singleton('lt.encaps', function($app){
            return $app->make(EncapsInterface::class);
        });
    }

    /**
     * This method do a binding to the Services|Commands
     * @param $services
     * @param bool $commands
     */
    protected function registerAction($services, $commands=false) {

        foreach ($services as $service) {
            $method = $service['method'];
            $abstract = $service['abstract'];
            $closure = $service['closure'];
            if($commands) {
                $this->listOfCommands[]=$service['abstract'];
            }

            $this->app->$method($abstract,$closure);
        }
    }
}