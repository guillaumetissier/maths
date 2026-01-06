<?php

declare(strict_types=1);

namespace Guillaumetissier\Maths\Matrix;

/**
 * Immutable matrix of floats.
 */
final class MatrixImmutable extends AbstractMatrix
{
    public function transpose(): self
    {
        return new MatrixImmutable($this->transpositionResult());
    }

    public function add(MatrixInterface $other): self
    {
        return new MatrixImmutable($this->additionResult($other));
    }

    public function multiply(MatrixInterface $other): self
    {
        return new MatrixImmutable($this->multiplicationResult($other));
    }
}
