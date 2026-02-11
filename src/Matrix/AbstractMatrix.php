<?php

declare(strict_types=1);

namespace Guillaumetissier\Maths\Matrix;

abstract class AbstractMatrix implements MatrixInterface, \JsonSerializable, \Stringable
{
    /** @var float[][] */
    protected array $data;

    protected int $numRows;

    protected int $numCols;

    public static function zeros(int $numRows, int $numCols): MatrixInterface
    {
        if ($numRows <= 0 || $numCols <= 0) {
            throw new \InvalidArgumentException('Invalid matrix dimensions');
        }

        $data = array_fill(0, $numRows, array_fill(0, $numCols, 0.0));

        return new static($data);
    }

    public static function ones(int $numRows, int $numCols): MatrixInterface
    {
        if ($numRows <= 0 || $numCols <= 0) {
            throw new \InvalidArgumentException('Invalid matrix dimensions');
        }

        $data = array_fill(0, $numRows, array_fill(0, $numCols, 1.0));

        return new static($data);
    }

    /**
     * @param float[][] $data
     */
    final public function __construct(array $data)
    {
        if (empty($data) || empty($data[0])) {
            throw new \InvalidArgumentException('Matrix cannot be empty');
        }

        $numCols = count($data[0]);
        foreach ($data as $row) {
            if (count($row) !== $numCols) {
                throw new \InvalidArgumentException('All rows must have the same number of columns');
            }
        }

        $this->data = $data;
        $this->numRows = count($data);
        $this->numCols = $numCols;
    }

    public function rows(): int
    {
        return $this->numRows;
    }

    public function cols(): int
    {
        return $this->numCols;
    }

    public function get(int $row, int $col): float
    {
        if ($row < 0 || $row >= $this->numRows || $col < 0 || $col >= $this->numCols) {
            throw new \OutOfBoundsException('Invalid row or column index');
        }

        return $this->data[$row][$col];
    }

    /**
     * @return float[]
     */
    public function row(int $row): array
    {
        if ($row < 0 || $row >= $this->numRows) {
            throw new \OutOfBoundsException('Invalid row index');
        }

        return $this->data[$row];
    }

    /**
     * @return float[]
     */
    public function column(int $col): array
    {
        if ($col < 0 || $col >= $this->numCols) {
            throw new \OutOfBoundsException('Invalid column index');
        }

        return array_map(fn ($row) => $row[$col], $this->data);
    }

    /**
     * @return float[][]
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * @return float[][]
     */
    public function jsonSerialize(): array
    {
        return $this->data;
    }

    public function __toString(): string
    {
        $rowsStr = array_map(fn ($row) => '('.implode(', ', $row).')', $this->data);

        return implode("\n", $rowsStr);
    }

    /**
     * @return float[][]
     */
    protected function transpositionResult(): array
    {
        $result = [];
        for ($i = 0; $i < $this->numCols; ++$i) {
            $result[$i] = $this->column($i);
        }

        return $result;
    }

    /**
     * @return float[][]
     */
    protected function additionResult(MatrixInterface $other): array
    {
        if ($this->numRows !== $other->rows() || $this->numCols !== $other->cols()) {
            throw new \InvalidArgumentException('Matrix dimensions must match for addition');
        }

        $result = [];
        for ($i = 0; $i < $this->numRows; ++$i) {
            $result[$i] = [];
            for ($j = 0; $j < $this->numCols; ++$j) {
                $result[$i][$j] = $this->data[$i][$j] + $other->get($i, $j);
            }
        }

        return $result;
    }

    /**
     * @return float[][]
     */
    public function multiplicationResult(MatrixInterface $other): array
    {
        if ($this->numCols !== $other->rows()) {
            throw new \InvalidArgumentException('Matrix multiplication dimension mismatch');
        }

        $result = [];
        for ($i = 0; $i < $this->numRows; ++$i) {
            $result[$i] = [];
            for ($j = 0; $j < $other->cols(); ++$j) {
                $sum = 0.0;
                for ($k = 0; $k < $this->numCols; ++$k) {
                    $sum += $this->data[$i][$k] * $other->get($k, $j);
                }
                $result[$i][$j] = $sum;
            }
        }

        return $result;
    }
}
