<?php

namespace Guillaumetissier\Maths\Number\Integer;

use Guillaumetissier\Maths\Number\AdditiveNumber;
use Guillaumetissier\Maths\Number\ComparableNumber;
use Guillaumetissier\Maths\Number\DivisibleNumber;
use Guillaumetissier\Maths\Number\MultiplicativeNumber;
use Guillaumetissier\Maths\Number\SignedNumber;

interface IntegerInterface extends AdditiveNumber, ComparableNumber, DivisibleNumber, MultiplicativeNumber, SignedNumber
{
    public function val(): int;
}
