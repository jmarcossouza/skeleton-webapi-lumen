<?php

namespace App\Exceptions;

/**
 * Exception que indica que a causa da Exception é o request do usuário.
 * `request_invalido`
 */
class InvalidRequestException extends CustomException
{
    public function __construct($message)
    {
        parent::__construct('request_invalido', $message, 400);
    }
}