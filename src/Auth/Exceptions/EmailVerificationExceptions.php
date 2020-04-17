<?php

namespace LTFramework\Auth\Exceptions;

use LTFramework\Exceptions\RendererException;

class EmailVerificationExceptions extends RendererException {

    /**
     * @var int $statusCode
     */
    protected $statusCode = 403;

    public function __construct($message, $statusCode = 403,  \Exception $previous = null, $code = 0)
    {
        $this->statusCode = $statusCode;

        parent::__construct($message, $code, $previous);
    }

    /**
     * This function return statusCode
     * 
     * @return int
     */
    public function getStatusCode() 
    {
        return $this->statusCode;
    }
}