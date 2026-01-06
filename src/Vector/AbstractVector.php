<?php

declare(strict_types=1);

namespace Guillaumetissier\Maths\Vector;

abstract class AbstractVector implements VectorInterface, \JsonSerializable, \Stringable
{
    /** @var float[] */
    protected array $components;

    protected function __construct(array $components)
    {
        if ([] === $components) {
            throw new \InvalidArgumentException('Vector cannot be empty.');
        }

        foreach ($components as $value) {
            if (!is_numeric($value)) {
                throw new \InvalidArgumentException('Vector components must be numeric.');
            }
        }

        $this->components = array_map('floatval', array_values($components));
    }

    public static function fromArray(array $components): self
    {
        return new static($components);
    }

    public static function zero(int $dimension): self
    {
        if ($dimension <= 0) {
            throw new \InvalidArgumentException('Dimension must be positive.');
        }

        return new static(array_fill(0, $dimension, 0.0));
    }

    public function dimension(): int
    {
        return count($this->components);
    }

    public function get(int $index): float
    {
        if (!array_key_exists($index, $this->components)) {
            throw new \OutOfBoundsException("Index $index out of bounds.");
        }

        return $this->components[$index];
    }

    public function toArray(): array
    {
        return $this->components;
    }

    public function norm(): float
    {
        return sqrt($this->dot($this));
    }

    public function normalize(): self
    {
        $norm = $this->norm();

        if (0.0 === $norm) {
            throw new \LogicException('Cannot normalize a zero vector.');
        }

        return $this->scale(1 / $norm);
    }

    public function equals(VectorInterface $other, float $epsilon = 1e-10): bool
    {
        if ($this->dimension() !== $other->dimension()) {
            return false;
        }

        foreach ($this->components as $i => $value) {
            if (abs($value - $other->components[$i]) > $epsilon) {
                return false;
            }
        }

        return true;
    }

    public function jsonSerialize(): array
    {
        return $this->components;
    }

    public function __toString(): string
    {
        return '('.implode(', ', $this->components).')';
    }

    abstract public function add(VectorInterface $other): self;

    abstract public function subtract(VectorInterface $other): self;

    abstract public function scale(float $scalar): self;

    public function dot(VectorInterface $other): float
    {
        $this->assertSameDimension($other);

        return array_sum(array_map(
            fn ($a, $b) => $a * $b,
            $this->components,
            $other->components
        ));
    }

    protected function assertSameDimension(VectorInterface $other): void
    {
        if ($this->dimension() !== $other->dimension()) {
            throw new \InvalidArgumentException('Vectors must have the same dimension.');
        }
    }
}
