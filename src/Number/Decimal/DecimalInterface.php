<?php

namespace Guillaumetissier\Maths\Number\Decimal;

use Guillaumetissier\Maths\Number\AdditiveNumber;
use Guillaumetissier\Maths\Number\ComparableNumber;
use Guillaumetissier\Maths\Number\DivisibleNumber;
use Guillaumetissier\Maths\Number\MultiplicativeNumber;
use Guillaumetissier\Maths\Number\SignedNumber;

interface DecimalInterface extends AdditiveNumber, ComparableNumber, DivisibleNumber, MultiplicativeNumber, SignedNumber
{
    public static function of(int $value, int $scale = 1): static;

    public function value(): int;

    public function scale(): int;

    public function val(): float;
}
