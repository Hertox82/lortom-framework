<?php

namespace LTFramework\Auth\Events;


class Verified 
{
   /**
    * @var $user
    */
    public $user;


    public function __construct($user)
    {
        $this->user = $user;
    }
}