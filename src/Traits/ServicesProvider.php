<?php

namespace LTFramework\Traits;


trait ServicesProvider 
{
     /**
     * This function register all services from LTFramework
     */
    public function registerServices() {

        $this->abstractRegister('Plugin');

        $this->abstractRegister('Template');

        $this->registerAction($this->getServicesBoot());
    }

     /**
     * This method register a type of Plugin|Template
     * @param $type string
     */
    public function abstractRegister($type) {
        $function = "get{$type}Compiler";

        $data = $this->$function();

        $this->registerAction($data);
    }

    /**
     * This function return an array of template-compiler
     * @return mixed array
     */
    protected function getTemplateCompiler() {
        return require __DIR__.'/../config/template-compiler.php';
    }

    /**
     * This function return an array of plugin-compiler
     * @return mixed array
     */
    protected function getPluginCompiler() {
        return require __DIR__.'/../config/plugin-compiler.php';
    }

     /**
     * This function return an array of services
     * @return mixed array
     */
    protected function getServicesBoot() {
        return require __DIR__.'/../config/services.php';
    }

}