<?php

namespace Services\Wallet\Exceptions;
use Throwable;

class ChargeCodeHasBeenUsedException extends \Exception {
    function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}