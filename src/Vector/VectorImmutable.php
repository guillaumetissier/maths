<?php

declare(strict_types=1);

namespace Guillaumetissier\Maths\Vector;

final class VectorImmutable extends AbstractVector
{
    public function add(VectorInterface $other): self
    {
        $this->assertSameDimension($other);

        return new VectorImmutable(array_map(
            fn ($a, $b) => $a + $b,
            $this->components,
            $other->toArray()
        ));
    }

    public function subtract(VectorInterface $other): self
    {
        $this->assertSameDimension($other);

        return new VectorImmutable(array_map(
            fn ($a, $b) => $a - $b,
            $this->components,
            $other->toArray()
        ));
    }

    public function scale(float $scalar): self
    {
        return new VectorImmutable(array_map(
            fn ($v) => $v * $scalar,
            $this->components
        ));
    }
}
