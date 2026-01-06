<?php

namespace Guillaumetissier\Maths;

interface StringParsable
{
    public static function parse(string $value): static;
}
