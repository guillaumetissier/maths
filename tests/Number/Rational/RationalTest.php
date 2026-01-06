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

final class RationalTest extends TestCase
{
    /**
     * @dataProvider dataCreation
     */
    public function testCreation(Rational $r, int $expectedNumerator, int $expectedDenominator): void
    {
        $this->assertSame($expectedNumerator, $r->numerator());
        $this->assertSame($expectedDenominator, $r->denominator());
    }

    public static function dataCreation(): \Generator
    {
        yield [Rational::of(5), 5, 1];
        yield [Rational::of(4, 2), 2, 1];
        yield [Rational::of(7, 3), 7, 3];
        yield [Rational::of(-15, 7), -15, 7];
        yield [Rational::of(-19, -7), 19, 7];
        yield [Rational::of(19, -13), -19, 13];
        yield [Rational::fromFloat(0.75), 3, 4];
        yield [Rational::fromFloat(-0.7500001, 6), -3, 4];
        yield [Rational::fromFloat(0.750001, 6), 750001, 1000000];
        yield [Rational::parse('1/2'), 1, 2];
        yield [Rational::parse('6 / 8'), 3, 4];
        yield [Rational::parse('7/-5'), -7, 5];
        yield [Rational::parse('-34/ 51'), -2, 3];
    }

    public function testParseInvalidFormat(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Rational::parse('abc');
    }

    public function testToIntExact(): void
    {
        $this->assertSame(2, Rational::parse('6/3')->toInt());
    }

    public function testToIntThrowsWhenNotExact(): void
    {
        $this->expectException(\LogicException::class);

        Rational::parse('3/2')->toInt();
    }

    /**
     * @dataProvider dataCompare
     */
    public function testCompare(Rational $r, ComparableNumber $comparedTo, $expectedResult): void
    {
        $this->assertSame($expectedResult, $r->compare($comparedTo));
    }

    public static function dataCompare(): \Generator
    {
        yield [Rational::parse('1/2'), Integer::of(2), -1];
        yield [Rational::parse('7/2'), IntegerImmutable::of(3), 1];
        yield [Rational::parse('6/2'), IntegerImmutable::of(3), 0];
        yield [Rational::parse('2/3'), Decimal::of(67, 2), -1];
        yield [Rational::parse('15/7'), DecimalImmutable::of(2), 1];
        yield [Rational::parse('-13/4'), Decimal::parse('-3.25'), 0];
        yield [Rational::parse('2/5'), Rational::of(4, 7), -1];
        yield [Rational::parse('12/5'), Rational::of(13, 7), 1];
        yield [Rational::parse('19/7'), Rational::of(38, 14), 0];
    }

    public function testJsonSerialize(): void
    {
        $r = Rational::parse('3/4');

        $this->assertSame('"3\/4"', json_encode($r));
    }

    public function testToString(): void
    {
        $r = Rational::parse('10/20');

        $this->assertSame('1/2', (string) $r);
    }

    /**
     * @dataProvider dataAdd
     */
    public function testAdd(Rational $r, AdditiveNumber $other, int $expectedNumerator, int $expectedDenominator): void
    {
        $result = $r->add($other);

        $this->assertSame($r, $result);
        $this->assertSame($expectedNumerator, $result->numerator());
        $this->assertSame($expectedDenominator, $result->denominator());
    }

    public static function dataAdd(): \Generator
    {
        yield [Rational::of(2, 3), Integer::of(3), 11, 3];
        yield [Rational::of(-4, 3), IntegerImmutable::of(4), 8, 3];
        yield [Rational::of(12, 5), Decimal::of(13, 1), 37, 10];
        yield [Rational::of(2, 4), DecimalImmutable::of(-5, 1), 0, 1];
        yield [Rational::of(2, 3), Rational::parse('3/4'), 17, 12];
        yield [Rational::of(1, 6), RationalImmutable::parse('-1 / 34'), 7, 51];
    }

    /**
     * @dataProvider dataSub
     */
    public function testSub(Rational $r, AdditiveNumber $other, int $expectedNumerator, int $expectedDenominator): void
    {
        $result = $r->sub($other);

        $this->assertSame($r, $result);
        $this->assertSame($expectedNumerator, $result->numerator());
        $this->assertSame($expectedDenominator, $result->denominator());
    }

    public static function dataSub(): \Generator
    {
        yield [Rational::of(2, 3), Integer::of(3), -7, 3];
        yield [Rational::of(-4, 3), IntegerImmutable::of(4), -16, 3];
        yield [Rational::of(12, 5), Decimal::of(13, 1), 11, 10];
        yield [Rational::of(2, 4), DecimalImmutable::of(-5, 1), 1, 1];
        yield [Rational::of(2, 3), Rational::parse('3/4'), -1, 12];
        yield [Rational::of(1, 6), RationalImmutable::parse('-1 / 34'), 10, 51];
    }

    /**
     * @dataProvider dataMul
     */
    public function testMul(
        Rational $r,
        MultiplicativeNumber $other,
        int $expectedNumerator,
        int $expectedDenominator,
    ): void {
        $result = $r->mul($other);

        $this->assertSame($r, $result);
        $this->assertSame($expectedNumerator, $result->numerator());
        $this->assertSame($expectedDenominator, $result->denominator());
    }

    public static function dataMul(): \Generator
    {
        yield [Rational::of(2, 3), Integer::of(3), 2, 1];
        yield [Rational::of(-4, 3), IntegerImmutable::of(4), -16, 3];
        yield [Rational::of(12, 5), Decimal::of(13, 1), 78, 25];
        yield [Rational::of(2, 4), DecimalImmutable::of(-5, 1), -1, 4];
        yield [Rational::of(2, 3), Rational::parse('3/4'), 1, 2];
        yield [Rational::of(1, 6), RationalImmutable::parse('-1 / 34'), -1, 204];
    }

    /**
     * @dataProvider dataDiv
     */
    public function testDiv(
        Rational $r,
        DivisibleNumber $other,
        int $expectedNumerator,
        int $expectedDenominator,
    ): void {
        $result = $r->div($other);

        $this->assertSame($r, $result);
        $this->assertSame($expectedNumerator, $result->numerator());
        $this->assertSame($expectedDenominator, $result->denominator());
    }

    public static function dataDiv(): \Generator
    {
        yield [Rational::of(2, 3), Integer::of(3), 2, 9];
        yield [Rational::of(-4, 3), IntegerImmutable::of(4), -1, 3];
        yield [Rational::of(12, 5), Decimal::of(13, 1), 24, 13];
        yield [Rational::of(2, 4), DecimalImmutable::of(-5, 1), -1, 1];
        yield [Rational::of(2, 3), Rational::parse('3/4'), 8, 9];
        yield [Rational::of(1, 6), RationalImmutable::parse('-1 / 34'), -17, 3];
    }

    /**
     * @dataProvider dataAbsolute
     */
    public function testAbsolute(Rational $r, int $expectedNumerator, int $expectedDenominator): void
    {
        $result = $r->abs();

        $this->assertSame($r, $result);
        $this->assertSame($expectedNumerator, $result->numerator());
        $this->assertSame($expectedDenominator, $result->denominator());
    }

    public static function dataAbsolute(): \Generator
    {
        yield [Rational::parse('14 /-21'), 2, 3];
        yield [Rational::parse('-3/-6'), 1, 2];
        yield [Rational::parse('-3 / 4'), 3, 4];
    }

    /**
     * @dataProvider dataNegate
     */
    public function testNegate(Rational $r, int $expectedNumerator, int $expectedDenominator): void
    {
        $result = $r->neg();

        $this->assertSame($r, $result);
        $this->assertSame($expectedNumerator, $result->numerator());
        $this->assertSame($expectedDenominator, $result->denominator());
    }

    public static function dataNegate(): \Generator
    {
        yield [Rational::parse('14/-21'), 2, 3];
        yield [Rational::parse('-3 / -6'), -1, 2];
        yield [Rational::parse('-3/4'), 3, 4];
    }
}
