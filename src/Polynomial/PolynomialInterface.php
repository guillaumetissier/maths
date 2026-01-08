<?php

namespace Guillaumetissier\Maths\Polynomial;

interface PolynomialInterface
{
    public function deg(): int;

    public function coef(int $degree);

    public function dominantCoef();
}
