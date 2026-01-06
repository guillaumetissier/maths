<?php

declare(strict_types=1);

namespace Guillaumetissier\Maths\Tests\Number\Integer;

use Guillaumetissier\Maths\Number\AdditiveNumber;
use Guillaumetissier\Maths\Number\ComparableNumber;
use Guillaumetissier\Maths\Number\Decimal\Decimal;
use Guillaumetissier\Maths\Number\Decimal\DecimalImmutable;
use Guillaumetissier\Maths\Number\DivisibleNumber;
use Guillaumetissier\Maths\Number\Integer\Integer;
use Guillaumetissier\Maths\Number\Integer\IntegerImmutable;
use Guillaumetissier\Maths\Number\MultiplicativeNumber;
use Guillaumetissier\Maths\Number\Rational\Rational;
use Guillaumetissier\Maths\Number\Rational\RationalImmutable;
use PHPUnit\Framework\TestCase;

final class IntegerTest extends TestCase
{
    /* ---------- Construction ---------- */

    /**
     * @dataProvider dataCreation
     */
    public function testCreation(Integer $integer, int $expectedValue): void
    {
        $this->assertSame($expectedValue, $integer->val());
    }

    public static function dataCreation(): \Generator
    {
        yield [Integer::of(-2), -2];
        yield [Integer::of(-2), -2];
        yield [Integer::parse('-17'), -17];
        yield [Integer::parse('23'), 23];
    }

    public function testToInteger(): void
    {
        $i = Integer::of(5);
        $i2 = $i->toInteger();

        $this->assertNotSame($i, $i2);
        $this->assertEquals(5, $i2->val());
    }

    public function testToDecimal(): void
    {
        $i = Integer::of(5);
        $d = $i->toDecimal();

        $this->assertEquals(5, $d->value());
        $this->assertEquals(0, $d->scale());
    }

    public function testToRational(): void
    {
        $i = Integer::of(5);
        $r = $i->toRational();

        $this->assertEquals(5, $r->numerator());
        $this->assertEquals(1, $r->denominator());
    }

    /* ---------- Comparison ---------- */

    /**
     * @dataProvider dataCompare
     */
    public function testCompare(Integer $d, ComparableNumber $other, int $expectedResult): void
    {
        $this->assertSame($expectedResult, $d->compare($other));
    }

    public static function dataCompare(): \Generator
    {
        yield [Integer::of(12), Integer::of(12), 0];
        yield [Integer::of(56), IntegerImmutable::of(5), 1];
        yield [Integer::of(23), Integer::of(234), -1];
        yield [Integer::of(12), Decimal::of(120, 1), 0];
        yield [Integer::of(56), DecimalImmutable::of(576, 1), -1];
        yield [Integer::of(23), Decimal::of(2256, 2), 1];
        yield [Integer::of(-12), Rational::of(24, -2), 0];
        yield [Integer::of(56), RationalImmutable::of(113, 2), -1];
        yield [Integer::of(23), Rational::of(91, 4), 1];
    }

    /* ---------- Serialization ---------- */

    public function testJsonSerialize(): void
    {
        $this->assertSame('"-101"', json_encode(Integer::parse('-101')));
    }

    public function testToString(): void
    {
        $this->assertSame('300', (string) Integer::parse('300'));
    }

    /* ---------- Arithmetic ---------- */

    /**
     * @dataProvider dataAddition
     */
    public function testAddition(Integer $d, AdditiveNumber $other, int $expected): void
    {
        $result = $d->add($other);

        $this->assertSame($expected, $d->val());
        $this->assertSame($expected, $result->val());
    }

    public static function dataAddition(): \Generator
    {
        yield [Integer::parse('3'), Integer::of(2), 5];
        yield [Integer::parse('-7'), IntegerImmutable::of(4), -3];
        yield [Integer::of(1), Decimal::parse('2.0'), 3];
        yield [Integer::of(12), DecimalImmutable::of(50, 1), 17];
        yield [Integer::parse('2'), Rational::of(5), 7];
        yield [Integer::of(-8), RationalImmutable::of(6, 2), -5];
    }

    /**
     * @dataProvider dataSubtraction
     */
    public function testSubtraction(Integer $d, AdditiveNumber $other, int $expected): void
    {
        $result = $d->sub($other);

        $this->assertSame($expected, $d->val());
        $this->assertSame($expected, $result->val());
    }

    public static function dataSubtraction(): \Generator
    {
        yield [Integer::parse('3'), Integer::of(2), 1];
        yield [Integer::parse('-7'), IntegerImmutable::of(4), -11];
        yield [Integer::of(1), Decimal::parse('2.0'), -1];
        yield [Integer::of(12), DecimalImmutable::of(50, 1), 7];
        yield [Integer::parse('2'), Rational::of(5), -3];
        yield [Integer::of(-8), RationalImmutable::of(6, 2), -11];
    }

    /**
     * @dataProvider dataMultiplication
     */
    public function testMultiplication(Integer $d, MultiplicativeNumber $other, int $expected): void
    {
        $result = $d->mul($other);

        $this->assertSame($expected, $d->val());
        $this->assertSame($expected, $result->val());
    }

    public static function dataMultiplication(): \Generator
    {
        yield [Integer::parse('3'), Integer::of(2), 6];
        yield [Integer::parse('-7'), IntegerImmutable::of(4), -28];
        yield [Integer::of(1), Decimal::parse('2.0'), 2];
        yield [Integer::of(12), DecimalImmutable::of(50, 1), 60];
        yield [Integer::parse('2'), Rational::of(5), 10];
        yield [Integer::of(-8), RationalImmutable::of(6, 2), -24];
    }

    /**
     * @dataProvider dataDivision
     */
    public function testDivision(Integer $d, DivisibleNumber $other, int $expected): void
    {
        $result = $d->div($other);

        $this->assertSame($expected, $d->val());
        $this->assertSame($expected, $result->val());
    }

    public static function dataDivision(): \Generator
    {
        yield [Integer::parse('12'), Integer::of(4), 3];
        yield [Integer::parse('-6'), IntegerImmutable::of(6), -1];
        yield [Integer::of(10), Decimal::parse('-5.000'), -2];
        yield [Integer::of(-102), DecimalImmutable::of(51), -2];
        yield [Integer::parse('15'), Rational::of(-3), -5];
        yield [Integer::of(38), RationalImmutable::of(38, 2), 2];
    }

    /* ---------- Unary operations ---------- */

    /**
     * @dataProvider dataAbs
     */
    public function testAbs(Integer $d, int $expected): void
    {
        $result = $d->abs();

        $this->assertSame($expected, $result->val());
        $this->assertSame($expected, $d->val());
    }

    public static function dataAbs(): \Generator
    {
        yield [Integer::parse('-1'), 1];
        yield [Integer::of(12345), 12345];
    }

    /**
     * @dataProvider dataNegate
     */
    public function testNegate(Integer $d, int $expected): void
    {
        $result = $d->neg();

        $this->assertSame($expected, $result->val());
        $this->assertSame($expected, $d->val());
    }

    public static function dataNegate(): \Generator
    {
        yield [Integer::parse('-1'), 1];
        yield [Integer::of(12345), -12345];
    }
}
