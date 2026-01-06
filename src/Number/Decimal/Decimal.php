<?php

namespace Guillaumetissier\Maths\Number\Decimal;

use Guillaumetissier\Maths\Number\AdditiveNumber;
use Guillaumetissier\Maths\Number\DivisibleNumber;
use Guillaumetissier\Maths\Number\MultiplicativeNumber;

final class Decimal extends AbstractDecimal
{
    public function add(AdditiveNumber $other): static
    {
        [$value, $scale] = $this->addition($other->toDecimal());
        [$this->value, $this->scale] = $this->reduceDecimal($value, $scale);

        return $this;
    }

    public function sub(AdditiveNumber $other): static
    {
        [$value, $scale] = $this->substraction($other->toDecimal());
        [$this->value, $this->scale] = $this->reduceDecimal($value, $scale);

        return $this;
    }

    public function mul(MultiplicativeNumber $other): static
    {
        [$value, $scale] = $this->multiplication($other->toDecimal());
        [$this->value, $this->scale] = $this->reduceDecimal($value, $scale);

        return $this;
    }

    public function div(DivisibleNumber $other): static
    {
        [$value, $scale] = $this->division($other->toRational());
        [$this->value, $this->scale] = $this->reduceDecimal($value, $scale);

        return $this;
    }

    public function abs(): static
    {
        $this->value = abs($this->value);

        return $this;
    }

    public function neg(): static
    {
        $this->value = -$this->value;

        return $this;
    }
}
