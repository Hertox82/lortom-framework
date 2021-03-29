<?php

namespace LTFramework\Model\Events;

class State {


    /**
     * This var accept this kind of value = create.object|update.object|delete.object 
     * 
     * @var string
     */ 
    public $state = '';

    public function __construct($state)
    {
        $this->state = $state;
    }
}