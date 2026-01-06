<?php

namespace Guillaumetissier\Maths\Number\Decimal;

use Guillaumetissier\Maths\Number\AdditiveNumber;
use Guillaumetissier\Maths\Number\DivisibleNumber;
use Guillaumetissier\Maths\Number\MultiplicativeNumber;

final class DecimalImmutable extends AbstractDecimal
{
    public function add(AdditiveNumber $other): static
    {
        [$value, $scale] = $this->addition($other->toDecimal());

        return new DecimalImmutable($value, $scale);
    }

    public function sub(AdditiveNumber $other): static
    {
        [$value, $scale] = $this->substraction($other->toDecimal());

        return new DecimalImmutable($value, $scale);
    }

    public function mul(MultiplicativeNumber $other): static
    {
        [$value, $scale] = $this->multiplication($other->toDecimal());

        return new DecimalImmutable($value, $scale);
    }

    public function div(DivisibleNumber $other): static
    {
        [$value, $scale] = $this->division($other->toRational());

        return new DecimalImmutable($value, $scale);
    }

    public function abs(): static
    {
        return new DecimalImmutable(abs($this->value), $this->scale);
    }

    public function neg(): static
    {
        return new DecimalImmutable(-$this->value, $this->scale);
    }
}
