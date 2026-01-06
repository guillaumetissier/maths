<?php

namespace Guillaumetissier\Maths\Exceptions;

class ParseException extends \Exception
{
    public function __construct($parsedString, ?\Throwable $previous = null)
    {
        parent::__construct("'$parsedString' cannot be parsed.", ExceptionCodes::ParseError, $previous);
    }
}
