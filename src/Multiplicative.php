<?php

namespace Guillaumetissier\Maths;

interface Multiplicative
{
    public function mul(mixed $other): static;
}
