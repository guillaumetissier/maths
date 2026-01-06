<?php

namespace Guillaumetissier\Maths\Number\Real;

use Guillaumetissier\Maths\Exceptions\NotYetImplementedException;
use Guillaumetissier\Maths\Number\AdditiveNumber;
use Guillaumetissier\Maths\Number\DivisibleNumber;
use Guillaumetissier\Maths\Number\MultiplicativeNumber;

class RealImmutable extends AbstractReal
{
    public function add(AdditiveNumber $other): static
    {
        // TODO: Implement add() method.
        throw new NotYetImplementedException('RealImmutable::add');
    }

    public function sub(AdditiveNumber $other): static
    {
        // TODO: Implement sub() method.
        throw new NotYetImplementedException('RealImmutable::sub');
    }

    public function div(DivisibleNumber $other): static
    {
        // TODO: Implement div() method.
        throw new NotYetImplementedException('RealImmutable::div');
    }

    public function mul(MultiplicativeNumber $other): static
    {
        // TODO: Implement mul() method.
        throw new NotYetImplementedException('RealImmutable::mul');
    }

    public function abs(): static
    {
        // TODO: Implement abs() method.
        throw new NotYetImplementedException('RealImmutable::abs');
    }

    public function neg(): static
    {
        // TODO: Implement neg() method.
        throw new NotYetImplementedException('RealImmutable::neg');
    }
}
