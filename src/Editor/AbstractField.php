<?php

namespace LTFramework\Editor;


abstract class AbstractField {
    protected $label = null;
    protected $placeholder = null;
    protected $name = null;   // field of database 
    protected $data = null;
    protected $isEdit = false;
    protected $type = null;
    protected $dataSerialize;
    protected $initialized = true;
    protected $db_table = '';
    protected $db_callable = null;
    protected $formatting = null;
    protected $available = true;


    public function init($args) {
        foreach($args as $key => $value) {
            // $this->setProperty($key,$value);
            $this->$key = $value;
        }

        return $this;
    }

    public function setProperty($key, $value) {
        if(property_exists($this,$key)) {
            $this->$key = $value;
        }
    }

    public function getProperty($key) {
        return (property_exists($this,$key)) ? $this->$key : null;
    }

    public function serialize() {
        $data = [];

        foreach($this->dataSerialize as $it) {
            $data['data'][$it] = $this->$it;
        }

        $data['type'] = $this->type;

        return $data;
    }

    function __set($key, $value) {
        if(property_exists($this,$key)) {
            $this->$key = $value;
        }
    }

    function __get($key) {
        return (property_exists($this,$key)) ? $this->$key : null;
    }
}