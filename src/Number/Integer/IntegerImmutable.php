<?php

declare(strict_types=1);

namespace Guillaumetissier\Maths\Number\Integer;

use Guillaumetissier\Maths\Exceptions\DivisionByZeroException;
use Guillaumetissier\Maths\Number\AdditiveNumber;
use Guillaumetissier\Maths\Number\DivisibleNumber;
use Guillaumetissier\Maths\Number\MultiplicativeNumber;

final class IntegerImmutable extends AbstractInteger
{
    /** ---- AdditiveNumber Interface ---- */
    public function add(AdditiveNumber $other): static
    {
        return new self($this->value + $other->toInteger()->val());
    }

    public function sub(AdditiveNumber $other): static
    {
        return new self($this->value - $other->toInteger()->val());
    }

    /** ---- MultiplicativeNumber Interface ---- */
    public function mul(MultiplicativeNumber $other): static
    {
        return new self($this->value * $other->toInteger()->val());
    }

    /**
     * ---- DivisibleNumber Interface ----.
     *
     * @throws DivisionByZeroException
     */
    public function div(DivisibleNumber $other): static
    {
        if (0 === $value = $other->toInteger()->val()) {
            throw new DivisionByZeroException();
        }

        return new self(intdiv($this->value, $value));
    }

    /** ---- SignedNumber Interface ---- */
    public function abs(): static
    {
        return new self(abs($this->value));
    }

    public function neg(): static
    {
        return new self(-$this->value);
    }
}
