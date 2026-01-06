<?php

namespace Guillaumetissier\Maths\Number\Real;

use Guillaumetissier\Maths\Number\AdditiveNumber;
use Guillaumetissier\Maths\Number\ComparableNumber;
use Guillaumetissier\Maths\Number\DivisibleNumber;
use Guillaumetissier\Maths\Number\MultiplicativeNumber;
use Guillaumetissier\Maths\Number\SignedNumber;

interface RealInterface extends AdditiveNumber, ComparableNumber, DivisibleNumber, MultiplicativeNumber, SignedNumber
{
}
