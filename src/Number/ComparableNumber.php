<?php

namespace Guillaumetissier\Maths\Number;

interface ComparableNumber extends Number
{
    public function compare(ComparableNumber $other): int;
}
