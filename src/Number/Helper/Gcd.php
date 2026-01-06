<?php

namespace Guillaumetissier\Maths\Number\Helper;

class Gcd
{
    public static function calculate(int $a, int $b): int
    {
        while (0 !== $b) {
            [$a, $b] = [$b, $a % $b];
        }

        return $a;
    }
}
