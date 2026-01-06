<?php

namespace Guillaumetissier\Maths\Number;

interface DivisibleNumber extends Number
{
    public function div(DivisibleNumber $other): static;
}
