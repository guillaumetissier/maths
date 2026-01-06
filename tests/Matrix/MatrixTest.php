<?php

declare(strict_types=1);

namespace Guillaumetissier\Maths\Tests\Matrix;

use Guillaumetissier\Maths\Matrix\Matrix;
use PHPUnit\Framework\TestCase;

final class MatrixTest extends TestCase
{
    public function testConstructAndGetters(): void
    {
        $data = [
            [1.0, 2.0],
            [3.0, 4.0],
        ];
        $matrix = new Matrix($data);

        $this->assertSame(2, $matrix->rows());
        $this->assertSame(2, $matrix->cols());

        $this->assertSame(1.0, $matrix->get(0, 0));
        $this->assertSame(4.0, $matrix->get(1, 1));

        $this->assertSame([1.0, 2.0], $matrix->row(0));
        $this->assertSame([2.0, 4.0], $matrix->column(1));
    }

    public function testInvalidConstruct(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Matrix([]);

        $this->expectException(\InvalidArgumentException::class);
        new Matrix([[]]);

        $this->expectException(\InvalidArgumentException::class);
        new Matrix([[1.0, 2.0], [3.0]]);
    }

    public function testOutOfBounds(): void
    {
        $matrix = new Matrix([[1.0, 2.0], [3.0, 4.0]]);

        $this->expectException(\OutOfBoundsException::class);
        $matrix->get(2, 0);

        $this->expectException(\OutOfBoundsException::class);
        $matrix->get(0, 2);

        $this->expectException(\OutOfBoundsException::class);
        $matrix->row(3);

        $this->expectException(\OutOfBoundsException::class);
        $matrix->column(5);
    }

    public function testTranspose(): void
    {
        $matrix = new Matrix([
            [1.0, 2.0, 3.0],
            [4.0, 5.0, 6.0],
        ]);
        $matrix->transpose();

        $this->assertSame(3, $matrix->rows());
        $this->assertSame(2, $matrix->cols());
        $this->assertSame([1.0, 4.0], $matrix->row(0));
        $this->assertSame([2.0, 5.0], $matrix->row(1));
        $this->assertSame([3.0, 6.0], $matrix->row(2));
    }

    public function testAdd(): void
    {
        $a = new Matrix([
            [1.0, 2.0],
            [3.0, 4.0],
        ]);
        $b = new Matrix([
            [5.0, 6.0],
            [7.0, 8.0],
        ]);
        $a->add($b);

        $this->assertSame(
            [
                [6.0, 8.0],
                [10.0, 12.0],
            ],
            $a->toArray()
        );
    }

    public function testAddDimensionMismatch(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $a = new Matrix([[1.0, 2.0]]);
        $b = new Matrix([[1.0, 2.0], [3.0, 4.0]]);
        $a->add($b);
    }

    public function testMultiply(): void
    {
        $a = new Matrix([
            [1.0, 2.0, 3.0],
            [4.0, 5.0, 6.0],
        ]);
        $b = new Matrix([
            [7.0, 8.0],
            [9.0, 10.0],
            [11.0, 12.0],
        ]);
        $a->multiply($b);

        $this->assertSame(
            [
                [58.0, 64.0],
                [139.0, 154.0],
            ],
            $a->toArray()
        );
    }

    public function testMultiplyDimensionMismatch(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $a = new Matrix([[1.0, 2.0]]);
        $b = new Matrix([
            [1.0, 2.0],
            [3.0, 4.0],
            [5.0, 6.0],
        ]);
        $a->multiply($b);
    }

    public function testJsonSerialize(): void
    {
        $matrix = new Matrix([
            [1.0, 2.2],
            [3.0, 4.1],
        ]);

        $json = json_encode($matrix);
        $this->assertJson($json);
        $this->assertSame([
            [1, 2.2],
            [3, 4.1],
        ], json_decode($json, true));
    }

    public function testToString(): void
    {
        $matrix = new Matrix([
            [1.0, 2.0],
            [3.0, 4.0],
        ]);

        $expected = "(1, 2)\n(3, 4)";
        $this->assertSame($expected, (string) $matrix);
    }
}
