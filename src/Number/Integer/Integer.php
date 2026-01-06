<?php

declare(strict_types=1);

namespace Guillaumetissier\Maths\Number\Integer;

use Guillaumetissier\Maths\Exceptions\DivisionByZeroException;
use Guillaumetissier\Maths\Number\AdditiveNumber;
use Guillaumetissier\Maths\Number\DivisibleNumber;
use Guillaumetissier\Maths\Number\MultiplicativeNumber;

final class Integer extends AbstractInteger
{
    /** ---- AdditiveNumber Interface ---- */
    public function add(AdditiveNumber $other): static
    {
        $this->value += $other->toInteger()->val();

        return $this;
    }

    public function sub(AdditiveNumber $other): static
    {
        $this->value -= $other->toInteger()->val();

        return $this;
    }

    /** ---- MultiplicativeNumber Interface ---- */
    public function mul(MultiplicativeNumber $other): static
    {
        $this->value *= $other->toInteger()->val();

        return $this;
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

        $this->value = intdiv($this->value, $value);

        return $this;
    }

    /** ---- SignedNumber Interface ---- */
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
