<?php

declare(strict_types=1);

namespace Guillaumetissier\Maths\Number\Rational;

use Guillaumetissier\Maths\Number\AdditiveNumber;
use Guillaumetissier\Maths\Number\ComparableNumber;
use Guillaumetissier\Maths\Number\DivisibleNumber;
use Guillaumetissier\Maths\Number\MultiplicativeNumber;
use Guillaumetissier\Maths\Number\SignedNumber;

interface RationalInterface extends AdditiveNumber, ComparableNumber, DivisibleNumber, MultiplicativeNumber, SignedNumber
{
    public static function of(int $numerator, int $denominator = 1): static;

    public function numerator(): int;

    public function denominator(): int;

    public function toInt(): int;

    public function toFloat(): float;
}
