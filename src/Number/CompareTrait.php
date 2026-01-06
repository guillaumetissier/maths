<?php

namespace Guillaumetissier\Maths\Number;

use Guillaumetissier\Maths\Number\Decimal\DecimalInterface;
use Guillaumetissier\Maths\Number\Integer\IntegerInterface;
use Guillaumetissier\Maths\Number\Rational\RationalInterface;
use Guillaumetissier\Maths\Number\Real\RealInterface;

trait CompareTrait
{
    protected function compareIntegers(IntegerInterface $a, IntegerInterface $b): int
    {
        return $a->val() <=> $b->val();
    }

    protected function compareDecimals(DecimalInterface $a, DecimalInterface $b): int
    {
        $scale = max($a->scale(), $b->scale());
        $v1 = $a->value() * (10 ** ($scale - $a->scale()));
        $v2 = $b->value() * (10 ** ($scale - $b->scale()));

        return $v1 <=> $v2;
    }

    protected function compareRationals(RationalInterface $a, RationalInterface $b): int
    {
        return $a->numerator() * $b->denominator() <=> $b->numerator() * $a->denominator();
    }

    protected function compareReals(RealInterface $a, RealInterface $b): int
    {
        // @todo compareReals
        return 0;
    }
}
