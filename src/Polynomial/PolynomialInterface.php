<?php

namespace Guillaumetissier\Maths\Polynomial;

use Guillaumetissier\Maths\Number\Rational\RationalInterface;

interface PolynomialInterface
{
    public function deg(): int;

    public function coef(int $degree): RationalInterface;

    public function dominantCoef(): RationalInterface;
}
