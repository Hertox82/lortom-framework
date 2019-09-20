<?php

namespace LTFramework\Services\Editor\Pipeline;

class RenderPipeline {

    protected static $instance;

    protected $renders = [];

    protected $storedFunctions = [];

    protected $functions = [];

    public static function getInstance() {
        if(is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    public static function addRender($params) {
        static::getInstance()->add($params);
    }

    public function add($param) {
        $this->renders[] = $param;
    }

    public function setFunctions($params) {
        $this->functions = $params;
    }

    public function getFunctions() {
        return $this->functions;
    }

    public static function renderByKey($key, $data) {
        // data può essere o soltanto la BuildEditView, soltanto la BuildViewList oppure
        // la Request insieme o alla BuildEditView o la BuildViewList
        $renderer = static::getInstance();
        $functions = $renderer->getItem($key);
        
        $renderer->setFunctions($functions);

        foreach($renderer->getFunctions() as $function) {
            list($class, $method) = explode('@', $function,2);
            $Object = new $class();
            $data = $Object->{$method}($data);
        }
       return $data;
    }

    public function getItem($key) {
        $response = [];

        foreach($this->renders as $render) {
            if($render['key'] === $key) {
                $response[] = $render['function'];
            }
        }

        return $response;
    }
}