<?php
/**
 * User: hernan
 * Date: 04/07/2018
 * Time: 09:51
 */

namespace LTFramework;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class LTFrameworkProvider extends ServiceProvider {


    protected $listOfCommands = [];

    protected $namespace = 'LTFramework\Controllers';

    public function boot() {

        $this->bootRouting();

    }


    public function register() {

        $this->registerServices();

        $this->registerCommand();

    }


    /**
     * This function register Console Command
     */
    protected function registerCommand() {

        $this->registerAction($this->getCommands(),true);

        $this->commands($this->listOfCommands);
    }

    /**
     * This function register all services from LTFramework
     */
    protected function registerServices() {

        $this->abstractRegister('Plugin');

        $this->abstractRegister('Template');
    }


    /**
     * This metho booting routes for web and api
     */
    protected function bootRouting() {

        if(!$this->app->routesAreCached()) {

            $this->mapWeb();

            $this->mapApi();
        }
    }

    /**
     * this method map web.php
     */
    protected function mapWeb() {

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(__DIR__.'/routes/web.php');
    }

    /**
     * this method map api.php
     */
    protected function mapApi() {

        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(__DIR__.'/routes/api.php');
    }

    /**
     * This method register a type of Plugin|Template
     * @param $type string
     */
    protected function abstractRegister($type) {
        $function = "get{$type}Compiler";

        $data = $this->$function();

        $this->registerAction($data);
    }

    /**
     * This function return an array of template-compiler
     * @return mixed array
     */
    protected function getTemplateCompiler() {
        return require __DIR__.'/config/template-compiler.php';
    }

    /**
     * This function return an array of plugin-compiler
     * @return mixed array
     */
    protected function getPluginCompiler() {
        return require __DIR__.'/config/plugin-compiler.php';
    }

    /**
     * This function return an array commands
     * @return mixed array
     */
    protected function getCommands() {
        return require __DIR__.'/config/commands.php';
    }

    /**
     * This method do a binding to the Services|Commands
     * @param $services
     * @param bool $commands
     */
    protected function registerAction($services,$commands=false) {

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