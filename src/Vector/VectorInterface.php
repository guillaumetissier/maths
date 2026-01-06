<?php

namespace Guillaumetissier\Maths\Vector;

interface VectorInterface
{
    public static function fromArray(array $components): self;

    public static function zero(int $dimension): self;

    /* ---------- Accessors ---------- */

    public function dimension(): int;

    public function get(int $index): float;

    public function toArray(): array;

    /* ---------- Algebra ---------- */

    public function add(VectorInterface $other): self;

    public function subtract(VectorInterface $other): self;

    public function scale(float $scalar): self;

    public function dot(VectorInterface $other): float;

    /* ---------- Norms ---------- */

    public function norm(): float;

    public function normalize(): self;

    /* ---------- Comparison ---------- */

    public function equals(VectorInterface $other, float $epsilon = 1e-10): bool;
}
