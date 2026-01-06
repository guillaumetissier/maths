<?php

declare(strict_types=1);

namespace Guillaumetissier\Maths\Matrix;

/**
 * Immutable matrix of floats.
 */
abstract class AbstractMatrix implements MatrixInterface, \JsonSerializable, \Stringable
{
    /** @var float[][] */
    protected array $data;

    protected int $rows;

    protected int $cols;

    /**
     * @param float[][] $data
     */
    public function __construct(array $data)
    {
        if (empty($data) || empty($data[0])) {
            throw new \InvalidArgumentException('Matrix cannot be empty');
        }

        $cols = count($data[0]);
        foreach ($data as $row) {
            if (!is_array($row) || count($row) !== $cols) {
                throw new \InvalidArgumentException('All rows must have the same number of columns');
            }
        }

        $this->data = $data;
        $this->rows = count($data);
        $this->cols = $cols;
    }

    public static function zeros(int $rows, int $cols): MatrixInterface
    {
        if ($rows <= 0 || $cols <= 0) {
            throw new \InvalidArgumentException('Invalid matrix dimensions');
        }

        $data = array_fill(0, $rows, array_fill(0, $cols, 0.0));

        return new static($data);
    }

    public function rows(): int
    {
        return $this->rows;
    }

    public function cols(): int
    {
        return $this->cols;
    }

    public function get(int $row, int $col): float
    {
        if ($row < 0 || $row >= $this->rows || $col < 0 || $col >= $this->cols) {
            throw new \OutOfBoundsException('Invalid row or column index');
        }

        return $this->data[$row][$col];
    }

    public function row(int $row): array
    {
        if ($row < 0 || $row >= $this->rows) {
            throw new \OutOfBoundsException('Invalid row index');
        }

        return $this->data[$row];
    }

    public function column(int $col): array
    {
        if ($col < 0 || $col >= $this->cols) {
            throw new \OutOfBoundsException('Invalid column index');
        }

        return array_map(fn ($row) => $row[$col], $this->data);
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function jsonSerialize(): array
    {
        return $this->data;
    }

    public function __toString(): string
    {
        $rowsStr = array_map(fn ($row) => '('.implode(', ', $row).')', $this->data);

        return implode("\n", $rowsStr);
    }

    protected function transpositionResult(): array
    {
        $result = [];
        for ($i = 0; $i < $this->cols; ++$i) {
            $result[$i] = $this->column($i);
        }

        return $result;
    }

    protected function additionResult(MatrixInterface $other): array
    {
        if ($this->rows !== $other->rows() || $this->cols !== $other->cols()) {
            throw new \InvalidArgumentException('Matrix dimensions must match for addition');
        }

        $result = [];
        for ($i = 0; $i < $this->rows; ++$i) {
            $result[$i] = [];
            for ($j = 0; $j < $this->cols; ++$j) {
                $result[$i][$j] = $this->data[$i][$j] + $other->get($i, $j);
            }
        }

        return $result;
    }

    public function multiplicationResult(MatrixInterface $other): array
    {
        if ($this->cols !== $other->rows()) {
            throw new \InvalidArgumentException('Matrix multiplication dimension mismatch');
        }

        $result = [];
        for ($i = 0; $i < $this->rows; ++$i) {
            $result[$i] = [];
            for ($j = 0; $j < $other->cols(); ++$j) {
                $sum = 0.0;
                for ($k = 0; $k < $this->cols; ++$k) {
                    $sum += $this->data[$i][$k] * $other->get($k, $j);
                }
                $result[$i][$j] = $sum;
            }
        }

        return $result;
    }
}
