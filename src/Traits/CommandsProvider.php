<?php

namespace LTFramework\Traits;


trait CommandsProvider 
{
    /**
     * This function register Console Command
     */
    public function registerCommand() {

        $this->registerAction($this->getCommands(),true);

        $this->commands($this->listOfCommands);
    }

    /**
     * This function return an array commands
     * @return mixed array
     */
    protected function getCommands() {
        return require __DIR__.'/../config/commands.php';
    }
}