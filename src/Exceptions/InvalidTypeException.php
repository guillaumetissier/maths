<?php

namespace Guillaumetissier\Maths\Exceptions;

class InvalidTypeException extends \Exception
{
    public static function cannotBeComparedTo(object $object): self
    {
        return new self(sprintf("Cannot compare to object type '%s'.", get_class($object)));
    }

    private function __construct(string $message)
    {
        parent::__construct($message, ExceptionCodes::InvalidType);
    }
}
