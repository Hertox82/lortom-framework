<?php

namespace LTFramework\Exceptions;

use Illuminate\Http\Request;

abstract class RendererException extends \Exception 
{
     /**
     * @var array $data
     */
    protected $data = [];

    public abstract function getStatusCode();
    /**
     * This function render an error page
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function render(Request $request) {
        $other = [
            'message' => $this->getMessage(),
            'error'   => $this->getStatusCode()
        ];
        $this->data = array_merge($this->data, $other);

        return  response()->view("error.{$this->statusCode}",$this->data, $this->getStatusCode());
    }

    /**
     * This function set a data for a view
     * 
     * @param array $data
     * @return void
     */
    public function prepareData($data) 
    {
        $this->data = $data;
    }
}