<?php

namespace LTFramework\Exceptions;

use Illuminate\Http\JsonResponse;
use RuntimeException;

class LTHttpException extends RuntimeException {

    /**
     * @var \Illuminate\Http\JsonResponse
     */
    protected $response;

    /**
     * @param \Illuminate\Http\JsonResponse $response
     * @return void
     */
    public function __construct(JsonResponse $response)
    {
        $this->response = $response;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getResponse() 
    {
        return $this->response;
    }
}