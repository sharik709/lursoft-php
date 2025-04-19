<?php

namespace Lursoft\LursoftPhp\Exceptions;

use Exception;

class LursoftException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
