<?php

namespace App\Exceptions;
use Throwable;

class ChargeCodeDoesNotExistsOrLimtCountReached extends \Exception {
    function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}