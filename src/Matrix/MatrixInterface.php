<?php

declare(strict_types=1);

namespace Guillaumetissier\Maths\Matrix;

/**
 * Immutable matrix of floats.
 */
interface MatrixInterface
{
    public static function zeros(int $rows, int $cols): MatrixInterface;

    public function rows(): int;

    public function cols(): int;

    public function get(int $row, int $col): float;

    public function row(int $row): array;

    public function column(int $col): array;

    public function transpose(): MatrixInterface;

    public function add(MatrixInterface $other): MatrixInterface;

    public function multiply(MatrixInterface $other): MatrixInterface;

    public function toArray(): array;
}
