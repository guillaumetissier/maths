<?php

namespace Guillaumetissier\Maths\Number;

interface ComparableNumber
{
    public function compare(ComparableNumber $other): int;
}
