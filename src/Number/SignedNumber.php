<?php

namespace Guillaumetissier\Maths\Number;

interface SignedNumber
{
    public function abs(): static;

    public function neg(): static;
}
