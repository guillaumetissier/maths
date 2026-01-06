<?php

declare(strict_types=1);

namespace Guillaumetissier\Maths\Tests\Vector;

use Guillaumetissier\Maths\Vector\Vector;
use PHPUnit\Framework\TestCase;

final class VectorTest extends TestCase
{
    public function testFromArrayCreatesVector(): void
    {
        $v = Vector::fromArray([1, 2, 3]);

        $this->assertSame(3, $v->dimension());
        $this->assertSame([1.0, 2.0, 3.0], $v->toArray());
    }

    public function testEmptyVectorIsNotAllowed(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Vector::fromArray([]);
    }

    public function testNonNumericComponentIsRejected(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Vector::fromArray([1, 'a', 3]);
    }

    public function testZeroVector(): void
    {
        $v = Vector::zero(3);

        $this->assertSame([0.0, 0.0, 0.0], $v->toArray());
    }

    public function testZeroVectorWithInvalidDimension(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Vector::zero(0);
    }

    public function testGetComponent(): void
    {
        $v = Vector::fromArray([10, 20, 30]);

        $this->assertSame(20.0, $v->get(1));
    }

    public function testGetOutOfBoundsThrows(): void
    {
        $this->expectException(\OutOfBoundsException::class);

        $v = Vector::fromArray([1, 2]);
        $v->get(5);
    }

    public function testAddition(): void
    {
        $v1 = Vector::fromArray([1, 2, 3]);
        $v2 = Vector::fromArray([4, 5, 6]);
        $v1->add($v2);

        $this->assertSame([5.0, 7.0, 9.0], $v1->toArray());
    }

    public function testSubtraction(): void
    {
        $v1 = Vector::fromArray([5, 7, 9]);
        $v2 = Vector::fromArray([1, 2, 3]);
        $v1->subtract($v2);

        $this->assertSame([4.0, 5.0, 6.0], $v1->toArray());
    }

    public function testAddWithDifferentDimensionsThrows(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $v1 = Vector::fromArray([1, 2]);
        $v2 = Vector::fromArray([1, 2, 3]);
        $v1->add($v2);
    }

    public function testScale(): void
    {
        $v = Vector::fromArray([1, -2, 3]);
        $v->scale(2);

        $this->assertSame([2.0, -4.0, 6.0], $v->toArray());
    }

    public function testDotProduct(): void
    {
        $v1 = Vector::fromArray([1, 2, 3]);
        $v2 = Vector::fromArray([4, -5, 6]);

        $this->assertSame(12.0, $v1->dot($v2));
    }

    public function testNorm(): void
    {
        $v = Vector::fromArray([3, 4]);

        $this->assertSame(5.0, $v->norm());
    }

    public function testNormalize(): void
    {
        $v = Vector::fromArray([3, 4]);
        $v->normalize();

        $this->assertTrue($v->equals(Vector::fromArray([0.6, 0.8])));
    }

    public function testNormalizeZeroVectorThrows(): void
    {
        $this->expectException(\LogicException::class);

        $v = Vector::zero(3);
        $v->normalize();
    }

    public function testEquals(): void
    {
        $v1 = Vector::fromArray([1.00000000001, 2, 3]);
        $v2 = Vector::fromArray([1.0, 2.0, 3.0]);

        $this->assertTrue($v1->equals($v2));
    }

    public function testNotEquals(): void
    {
        $v1 = Vector::fromArray([1, 2, 3]);
        $v2 = Vector::fromArray([1, 2, 4]);

        $this->assertFalse($v1->equals($v2));
    }

    public function testJsonSerialize(): void
    {
        $v = Vector::fromArray([1, 2, 3]);

        $this->assertSame('[1,2,3]', json_encode($v));
    }

    public function testToString(): void
    {
        $v = Vector::fromArray([1, 2, 3]);

        $this->assertSame('(1, 2, 3)', (string) $v);
    }
}
