<?php

declare(strict_types=1);

namespace Guillaumetissier\Maths\Tests\Number\Rational;

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

final class RationalImmutableTest extends TestCase
{
    public function testCreationFromInt(): void
    {
        $r = RationalImmutable::of(5);

        $this->assertSame(5, $r->numerator());
        $this->assertSame(1, $r->denominator());
    }

    public function testFromFloat(): void
    {
        $r = RationalImmutable::fromFloat(0.75);

        $this->assertSame(3, $r->numerator());
        $this->assertSame(4, $r->denominator());
    }

    public function testFromFloatWithPrecision(): void
    {
        $r = RationalImmutable::fromFloat(0.3333333, 6);

        $this->assertSame(333333, $r->numerator());
        $this->assertSame(1000000, $r->denominator());
    }

    public function testFromStringInvalidFormat(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        RationalImmutable::parse('abc');
    }

    public function testNormalization(): void
    {
        $r = RationalImmutable::parse('6/8');

        $this->assertSame(3, $r->numerator());
        $this->assertSame(4, $r->denominator());
    }

    public function testNegativeDenominatorIsCanonicalized(): void
    {
        $r = RationalImmutable::parse('1/-2');

        $this->assertSame(-1, $r->numerator());
        $this->assertSame(2, $r->denominator());
    }

    public function testToIntExact(): void
    {
        $r = RationalImmutable::parse('6/3');

        $this->assertSame(2, $r->toInt());
    }

    public function testToIntThrowsWhenNotExact(): void
    {
        $r = RationalImmutable::parse('3/2');

        $this->expectException(\LogicException::class);

        $r->toInt();
    }

    /**
     * @dataProvider dataCompare
     */
    public function testCompare(RationalImmutable $r, ComparableNumber $comparedTo, $expectedResult): void
    {
        $this->assertSame($expectedResult, $r->compare($comparedTo));
    }

    public static function dataCompare(): \Generator
    {
        yield [RationalImmutable::parse('1/2'), Integer::of(2), -1];
        yield [RationalImmutable::parse('7/2'), IntegerImmutable::of(3), 1];
        yield [RationalImmutable::parse('6/2'), IntegerImmutable::of(3), 0];
        yield [RationalImmutable::parse('2/3'), Decimal::of(67, 2), -1];
        yield [RationalImmutable::parse('15/7'), DecimalImmutable::of(2), 1];
        yield [RationalImmutable::parse('-13/4'), Decimal::parse('-3.25'), 0];
        yield [RationalImmutable::parse('2/5'), Rational::of(4, 7), -1];
        yield [RationalImmutable::parse('12/5'), Rational::of(13, 7), 1];
        yield [RationalImmutable::parse('19/7'), Rational::of(38, 14), 0];
    }

    public function testJsonSerialize(): void
    {
        $r = RationalImmutable::parse('3/4');

        $this->assertSame('"3\/4"', json_encode($r));
    }

    public function testToString(): void
    {
        $r = RationalImmutable::parse('10/20');

        $this->assertSame('1/2', (string) $r);
    }

    /**
     * @dataProvider dataAdd
     */
    public function testAdd(
        RationalImmutable $r,
        AdditiveNumber $other,
        int $expectedNumerator,
        int $expectedDenominator,
    ): void {
        $result = $r->add($other);

        $this->assertNotSame($r, $result);
        $this->assertSame($expectedNumerator, $result->numerator());
        $this->assertSame($expectedDenominator, $result->denominator());
    }

    public static function dataAdd(): \Generator
    {
        yield [RationalImmutable::of(2, 3), Integer::of(3), 11, 3];
        yield [RationalImmutable::of(-4, 3), IntegerImmutable::of(4), 8, 3];
        yield [RationalImmutable::of(12, 5), Decimal::of(13, 1), 37, 10];
        yield [RationalImmutable::of(2, 4), DecimalImmutable::of(-5, 1), 0, 1];
        yield [RationalImmutable::of(2, 3), Rational::parse('3/4'), 17, 12];
        yield [RationalImmutable::of(1, 6), RationalImmutable::parse('-1 / 34'), 7, 51];
    }

    /**
     * @dataProvider dataSub
     */
    public function testSub(
        RationalImmutable $r,
        AdditiveNumber $other,
        int $expectedNumerator,
        int $expectedDenominator,
    ): void {
        $result = $r->sub($other);

        $this->assertNotSame($r, $result);
        $this->assertSame($expectedNumerator, $result->numerator());
        $this->assertSame($expectedDenominator, $result->denominator());
    }

    public static function dataSub(): \Generator
    {
        yield [RationalImmutable::of(2, 3), Integer::of(3), -7, 3];
        yield [RationalImmutable::of(-4, 3), IntegerImmutable::of(4), -16, 3];
        yield [RationalImmutable::of(12, 5), Decimal::of(13, 1), 11, 10];
        yield [RationalImmutable::of(2, 4), DecimalImmutable::of(-5, 1), 1, 1];
        yield [RationalImmutable::of(2, 3), Rational::parse('3/4'), -1, 12];
        yield [RationalImmutable::of(1, 6), RationalImmutable::parse('-1 / 34'), 10, 51];
    }

    /**
     * @dataProvider dataMul
     */
    public function testMul(
        RationalImmutable $r,
        MultiplicativeNumber $other,
        int $expectedNumerator,
        int $expectedDenominator,
    ): void {
        $result = $r->mul($other);

        $this->assertNotSame($r, $result);
        $this->assertSame($expectedNumerator, $result->numerator());
        $this->assertSame($expectedDenominator, $result->denominator());
    }

    public static function dataMul(): \Generator
    {
        yield [RationalImmutable::of(2, 3), Integer::of(3), 2, 1];
        yield [RationalImmutable::of(-4, 3), IntegerImmutable::of(4), -16, 3];
        yield [RationalImmutable::of(12, 5), Decimal::of(13, 1), 78, 25];
        yield [RationalImmutable::of(2, 4), DecimalImmutable::of(-5, 1), -1, 4];
        yield [RationalImmutable::of(2, 3), Rational::parse('3/4'), 1, 2];
        yield [RationalImmutable::of(1, 6), RationalImmutable::parse('-1 / 34'), -1, 204];
    }

    /**
     * @dataProvider dataDiv
     */
    public function testDiv(
        RationalImmutable $r,
        DivisibleNumber $other,
        int $expectedNumerator,
        int $expectedDenominator,
    ): void {
        $result = $r->div($other);

        $this->assertNotSame($r, $result);
        $this->assertSame($expectedNumerator, $result->numerator());
        $this->assertSame($expectedDenominator, $result->denominator());
    }

    public static function dataDiv(): \Generator
    {
        yield [RationalImmutable::of(2, 3), Integer::of(3), 2, 9];
        yield [RationalImmutable::of(-4, 3), IntegerImmutable::of(4), -1, 3];
        yield [RationalImmutable::of(12, 5), Decimal::of(13, 1), 24, 13];
        yield [RationalImmutable::of(2, 4), DecimalImmutable::of(-5, 1), -1, 1];
        yield [RationalImmutable::of(2, 3), Rational::parse('3/4'), 8, 9];
        yield [RationalImmutable::of(1, 6), RationalImmutable::parse('-1 / 34'), -17, 3];
    }

    /**
     * @dataProvider dataAbsolute
     */
    public function testAbsolute(RationalImmutable $r, int $expectedNumerator, int $expectedDenominator): void
    {
        $result = $r->abs();

        $this->assertNotSame($r, $result);
        $this->assertSame($expectedNumerator, $result->numerator());
        $this->assertSame($expectedDenominator, $result->denominator());
    }

    public static function dataAbsolute(): \Generator
    {
        yield [RationalImmutable::parse('14 /-21'), 2, 3];
        yield [RationalImmutable::parse('-3/-6'), 1, 2];
        yield [RationalImmutable::parse('-3 / 4'), 3, 4];
    }

    /**
     * @dataProvider dataNegate
     */
    public function testNegate(RationalImmutable $r, int $expectedNumerator, int $expectedDenominator): void
    {
        $result = $r->neg();

        $this->assertNotSame($r, $result);
        $this->assertSame($expectedNumerator, $result->numerator());
        $this->assertSame($expectedDenominator, $result->denominator());
    }

    public static function dataNegate(): \Generator
    {
        yield [RationalImmutable::parse('14/-21'), 2, 3];
        yield [RationalImmutable::parse('-3 / -6'), -1, 2];
        yield [RationalImmutable::parse('-3/4'), 3, 4];
    }
}
