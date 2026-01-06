<?php

namespace Guillaumetissier\Maths\Exceptions;

class NotYetImplementedException extends \Exception
{
    public function __construct(string $function, ?\Throwable $previous = null)
    {
        parent::__construct("function '$function' not yet implemented.", ExceptionCodes::NotYetImplemented, $previous);
    }
}
