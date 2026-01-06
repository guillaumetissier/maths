<?php

declare(strict_types=1);

namespace Guillaumetissier\Maths\Vector;

final class Vector extends AbstractVector
{
    public function add(VectorInterface $other): self
    {
        $this->assertSameDimension($other);

        $this->components = array_map(
            fn ($a, $b) => $a + $b,
            $this->components,
            $other->toArray()
        );

        return $this;
    }

    public function subtract(VectorInterface $other): self
    {
        $this->assertSameDimension($other);

        $this->components = array_map(
            fn ($a, $b) => $a - $b,
            $this->components,
            $other->toArray()
        );

        return $this;
    }

    public function scale(float $scalar): self
    {
        $this->components = array_map(
            fn ($v) => $v * $scalar,
            $this->components
        );

        return $this;
    }
}
