<?php

declare(strict_types=1);

namespace Guillaumetissier\Maths\Matrix;

/**
 * Mutable matrix of floats.
 */
final class Matrix extends AbstractMatrix
{
    public function transpose(): self
    {
        $this->data = $this->transpositionResult();
        $oldRows = $this->numRows;
        $this->numRows = $this->numCols;
        $this->numCols = $oldRows;

        return $this;
    }

    public function add(MatrixInterface $other): self
    {
        $this->data = $this->additionResult($other);

        return $this;
    }

    public function multiply(MatrixInterface $other): self
    {
        $this->data = $this->multiplicationResult($other);
        $this->numCols = $other->cols();

        return $this;
    }
}
