<?php

declare(strict_types=1);

namespace Guillaumetissier\Maths\Exceptions;

class ConversionException extends \LogicException
{
    public function __construct(string $type, ?\Throwable $previous = null)
    {
        parent::__construct("Cannot convert to '$type'", ExceptionCodes::ConversionError, $previous);
    }
}
