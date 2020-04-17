<?php

namespace LTFramework\Traits;


trait EditorProvider 
{
    /**
     * This function register all Editor Classes
     */
    public function registerEditor() {
        $this->registerAction($this->getEditorBoot());
    }

     /**
     * This function return an array of editor-boot
     * @return mixed array
     */
    protected function getEditorBoot() {
        return require __DIR__.'/../config/editor-boot.php';
    }
}