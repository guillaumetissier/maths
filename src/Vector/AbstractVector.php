<?php

declare(strict_types=1);

namespace Guillaumetissier\Maths\Vector;

abstract class AbstractVector implements VectorInterface, \JsonSerializable, \Stringable
{
    /** @var float[] */
    protected array $components = [];

    /**
     * @param mixed[] $components
     */
    final protected function __construct(array $components)
    {
        if ([] === $components) {
            throw new \InvalidArgumentException('Vector cannot be empty.');
        }

        foreach ($components as $value) {
            if (!is_numeric($value)) {
                throw new \InvalidArgumentException('Vector components must be numeric.');
            }
        }

        $this->components = array_map(
            static fn (mixed $value): float => is_scalar($value) ? floatval($value) : 0.0,
            array_values($components)
        );
    }

    public static function fromArray(array $components): static
    {
        return new static($components);
    }

    public static function zero(int $dimension): static
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

    /**
     * @return float[]
     */
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
            if (abs($value - $other->get($i)) > $epsilon) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return float[]
     */
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
            fn (float $a, float $b) => $a * $b,
            $this->components,
            $other->toArray()
        ));
    }

    protected function assertSameDimension(VectorInterface $other): void
    {
        if ($this->dimension() !== $other->dimension()) {
            throw new \InvalidArgumentException('Vectors must have the same dimension.');
        }
    }
}
