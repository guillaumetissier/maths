<?php

declare(strict_types=1);

namespace Guillaumetissier\Maths\Tests\Number\Decimal;

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

final class DecimalImmutableTest extends TestCase
{
    /* ---------- Construction ---------- */

    public function testOfCreatesDecimal(): void
    {
        $d = DecimalImmutable::of(12345, 2);

        $this->assertSame(12345, $d->value());
        $this->assertSame(2, $d->scale());
        $this->assertSame('123.45', (string) $d);
    }

    /**
     * @dataProvider dataParseCreatesDecimal
     */
    public function testParseCreatesDecimal(
        string $parsedString,
        int $expectedValue,
        int $expectedScale,
        string $expectedString,
    ): void {
        $d = DecimalImmutable::parse($parsedString);

        $this->assertSame($expectedValue, $d->value());
        $this->assertSame($expectedScale, $d->scale());
        $this->assertSame($expectedString, (string) $d);
    }

    public static function dataParseCreatesDecimal(): \Generator
    {
        yield ['0.075', 75, 3, '0.075'];
        yield ['1.2356', 12356, 4, '1.2356'];
        yield ['-12.50', -125, 1, '-12.5'];
        yield ['-0.00243000', -243, 5, '-0.00243'];
    }

    /* ---------- Value access ---------- */

    public function testValReturnsFloatApproximation(): void
    {
        $d = DecimalImmutable::of(1, 3); // 0.001

        $this->assertEquals(0.001, $d->val());
    }

    /* ---------- Comparison ---------- */

    /**
     * @dataProvider dataCompareSameScale
     */
    public function testCompareSameScale(DecimalImmutable $d, ComparableNumber $other, int $expectedResult): void
    {
        $this->assertSame($expectedResult, $d->compare($other));
    }

    public static function dataCompareSameScale(): \Generator
    {
        yield [DecimalImmutable::of(120, 2), DecimalImmutable::of(12, 1), 0];
        yield [DecimalImmutable::of(567, 7), DecimalImmutable::of(567, 8), 1];
        yield [DecimalImmutable::of(2345, 4), DecimalImmutable::of(2345, 1), -1];
        yield [DecimalImmutable::of(98, 1), DecimalImmutable::of(98, 1), 0];
    }

    /* ---------- Arithmetic ---------- */

    /**
     * @dataProvider dataAddition
     */
    public function testAddition(DecimalImmutable $d, AdditiveNumber $other, string $expected): void
    {
        $result = $d->add($other);

        $this->assertNotSame($expected, (string) $d);
        $this->assertSame($expected, (string) $result);
    }

    public static function dataAddition(): \Generator
    {
        yield [DecimalImmutable::parse('3.78'), Integer::of(2), '5.78'];
        yield [DecimalImmutable::parse('7.3454'), IntegerImmutable::of(4), '11.3454'];
        yield [DecimalImmutable::parse('1.25'), DecimalImmutable::parse('0.75'), '2'];
        yield [DecimalImmutable::parse('12.1'), DecimalImmutable::parse('5.75'), '17.85'];
        yield [DecimalImmutable::parse('2.51'), Rational::of(1, 4), '2.76'];
        yield [DecimalImmutable::parse('8.45'), RationalImmutable::of(2, 5), '8.85'];
    }

    /**
     * @dataProvider dataSubtraction
     */
    public function testSubtraction(DecimalImmutable $d, AdditiveNumber $other, string $expected): void
    {
        $result = $d->sub($other);

        $this->assertNotSame($expected, (string) $d);
        $this->assertSame($expected, (string) $result);
    }

    public static function dataSubtraction(): \Generator
    {
        yield [DecimalImmutable::parse('3.78'), Integer::of(2), '1.78'];
        yield [DecimalImmutable::parse('7.3454'), IntegerImmutable::of(4), '3.3454'];
        yield [DecimalImmutable::parse('1.25'), DecimalImmutable::parse('4.75'), '-3.5'];
        yield [DecimalImmutable::parse('12.1'), DecimalImmutable::parse('-5.75'), '17.85'];
        yield [DecimalImmutable::parse('2.51'), Rational::of(1, 4), '2.26'];
        yield [DecimalImmutable::parse('8.45'), RationalImmutable::of(2, 5), '8.05'];
    }

    /**
     * @dataProvider dataMultiplication
     */
    public function testMultiplication(DecimalImmutable $d, MultiplicativeNumber $other, string $expected): void
    {
        $result = $d->mul($other);

        $this->assertNotSame($expected, (string) $d);
        $this->assertSame($expected, (string) $result);
    }

    public static function dataMultiplication(): \Generator
    {
        yield [DecimalImmutable::parse('3.78'), Integer::of(2), '7.56'];
        yield [DecimalImmutable::parse('7.3454'), IntegerImmutable::of(4), '29.3816'];
        yield [DecimalImmutable::parse('1.25'), DecimalImmutable::parse('-4.75'), '-5.9375'];
        yield [DecimalImmutable::parse('12.1'), DecimalImmutable::parse('5.75'), '69.575'];
        yield [DecimalImmutable::parse('-2.51'), Rational::of(1, 4), '-0.6275'];
        yield [DecimalImmutable::parse('8.45'), RationalImmutable::of(12, 5), '20.28'];
    }

    /**
     * @dataProvider dataDivision
     */
    public function testDivision(DecimalImmutable $d, DivisibleNumber $other, string $expected): void
    {
        $result = $d->div($other);

        $this->assertNotSame($d, $result);
        $this->assertSame($expected, (string) $result);
    }

    public static function dataDivision(): \Generator
    {
        yield [DecimalImmutable::parse('2.24'), Integer::of(4), '0.56'];
        yield [DecimalImmutable::parse('-3.66'), IntegerImmutable::of(6), '-0.61'];
        yield [DecimalImmutable::parse('1.50'), Decimal::parse('0.5000'), '3'];
        yield [DecimalImmutable::parse('0.153'), DecimalImmutable::of(51, 1), '0.03'];
        yield [DecimalImmutable::parse('1.50'), Rational::of(-2, 3), '-2.25'];
        yield [DecimalImmutable::parse('2.23'), RationalImmutable::of(5, 17), '7.582'];
    }

    /* ---------- Unary operations ---------- */

    /**
     * @dataProvider dataAbsolute
     */
    public function testAbsolute(DecimalImmutable $d, string $expected): void
    {
        $this->assertSame($expected, (string) $d->abs());
    }

    public static function dataAbsolute(): \Generator
    {
        yield [DecimalImmutable::parse('-1.25'), '1.25'];
        yield [DecimalImmutable::of(12345, 2), '123.45'];
    }

    /**
     * @dataProvider dataNegate
     */
    public function testNegate(DecimalImmutable $d, string $expected): void
    {
        $this->assertSame($expected, (string) $d->neg());
    }

    public static function dataNegate(): \Generator
    {
        yield [DecimalImmutable::parse('-1.25'), '1.25'];
        yield [DecimalImmutable::of(12345, 2), '-123.45'];
    }

    /* ---------- Conversions ---------- */

    public function testToIntegerExact(): void
    {
        $this->assertSame(5, DecimalImmutable::parse('5.0')->toInteger()->val());
    }

    public function testToDecimalReturnsSelf(): void
    {
        $d = DecimalImmutable::parse('1.25');

        $this->assertNotSame($d, $d->toDecimal());
        $this->assertSame('1.25', (string) $d->toDecimal());
    }

    public function testToRational(): void
    {
        $r = DecimalImmutable::parse('0.75')->toRational();

        $this->assertSame(3, $r->numerator());
        $this->assertSame(4, $r->denominator());
    }

    //    public function testToReal(): void
    //    {
    //        $r = DecimalImmutable::parse('1.25')->toReal();
    //
    //        $this->assertEquals(1.25, $r->val());
    //    }

    /* ---------- Serialization ---------- */

    public function testJsonSerialize(): void
    {
        $d = DecimalImmutable::parse('2.370');

        $this->assertSame('"2.37"', json_encode($d));
    }

    public function testToStringPreservesScale(): void
    {
        $d = DecimalImmutable::parse('2.600');

        $this->assertSame('2.6', (string) $d);
    }
}
