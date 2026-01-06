<?php

declare(strict_types=1);

namespace Guillaumetissier\Maths\Number\Rational;

use Guillaumetissier\Maths\Number\AdditiveNumber;
use Guillaumetissier\Maths\Number\DivisibleNumber;
use Guillaumetissier\Maths\Number\MultiplicativeNumber;

final class RationalImmutable extends AbstractRational
{
    public function add(AdditiveNumber $other): static
    {
        $rational = $other->toRational();

        return new RationalImmutable(
            $this->numerator * $rational->denominator() + $rational->numerator() * $this->denominator,
            $this->denominator * $rational->denominator()
        );
    }

    public function sub(AdditiveNumber $other): static
    {
        $rational = $other->toRational();

        return new RationalImmutable(
            $this->numerator * $rational->denominator() - $rational->numerator() * $this->denominator,
            $this->denominator * $rational->denominator()
        );
    }

    public function mul(MultiplicativeNumber $other): static
    {
        $rational = $other->toRational();

        return new RationalImmutable(
            $this->numerator * $rational->numerator(),
            $this->denominator * $rational->denominator()
        );
    }

    public function div(DivisibleNumber $other): static
    {
        $rational = $other->toRational();

        if (0 === $rational->numerator()) {
            throw new \DivisionByZeroError('Cannot divide by zero.');
        }

        return new RationalImmutable(
            $this->numerator * $rational->denominator(),
            $this->denominator * $rational->numerator()
        );
    }

    public function abs(): static
    {
        return new RationalImmutable(abs($this->numerator), $this->denominator);
    }

    public function neg(): static
    {
        return new RationalImmutable(-$this->numerator, $this->denominator);
    }
}
