<?php

namespace LTFramework\Traits;

use Illuminate\Support\Facades\Route;

trait RoutingProvider 
{
     /**
     * This metho booting routes for web and api
     */
    public function bootRouting() {

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
            ->group(__DIR__.'/../routes/web.php');
    }

    /**
     * this method map api.php
     */
    protected function mapApi() {

        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(__DIR__.'/../routes/api.php');
    }
}