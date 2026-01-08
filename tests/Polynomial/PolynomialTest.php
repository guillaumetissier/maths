<?php

namespace Guillaumetissier\Maths\Tests\Polynomial;

use Guillaumetissier\Maths\Polynomial\Polynomial;
use Guillaumetissier\Maths\Polynomial\PolynomialImmutable;
use Guillaumetissier\Maths\Polynomial\PolynomialInterface;
use PHPUnit\Framework\TestCase;

class PolynomialTest extends TestCase
{
    /**
     * @dataProvider dataAdd
     */
    public function testAdd(Polynomial $p1, PolynomialInterface $p2, string $expected): void
    {
        $result = $p1->add($p2);

        $this->assertSame($expected, (string) $p1);
        $this->assertSame($expected, (string) $result);
    }

    public static function dataAdd(): \Generator
    {
        yield [
            Polynomial::parse('x^2 + x + 1'),
            Polynomial::parse('x^2 + x + 1'),
            '2x^2 + 2x + 2',
        ];

        yield [
            Polynomial::parse('x^2 + x + 1'),
            PolynomialImmutable::parse('-1x^2 + 1'),
            'x + 2',
        ];

        yield [
            Polynomial::parse('2.3x^2 + x + 1'),
            Polynomial::parse('-1x^2 + 1'),
            '13/10x^2 + x + 2',
        ];

        yield [
            Polynomial::parse('1/2x^2 + 0.5x + 1'),
            PolynomialImmutable::parse('-1/3x^2 + 1/7'),
            '1/6x^2 + 1/2x + 8/7',
        ];
    }

    /**
     * @dataProvider dataSub
     */
    public function testSub(Polynomial $p1, PolynomialInterface $p2, string $expected): void
    {
        $result = $p1->sub($p2);

        $this->assertSame($expected, (string) $p1);
        $this->assertSame($expected, (string) $result);
    }

    public static function dataSub(): \Generator
    {
        yield [
            Polynomial::parse('2.x^2 + 2*x + 2'),
            Polynomial::parse('x^2 + x + 1'),
            'x^2 + x + 1',
        ];

        yield [
            Polynomial::parse('x^2 + x + 1'),
            PolynomialImmutable::parse('-1x^2 + 1'),
            '2x^2 + x',
        ];
    }

    /**
     * @dataProvider dataMul
     */
    public function testMul(Polynomial $p1, PolynomialInterface $p2, string $expected): void
    {
        $result = $p1->mul($p2);

        $this->assertSame($expected, (string) $p1);
        $this->assertSame($expected, (string) $result);
    }

    public static function dataMul(): \Generator
    {
        yield [
            Polynomial::parse('2.x^2 + 2*x + 2'),
            Polynomial::parse('x^2 + x + 1'),
            '2x^4 + 4x^3 + 6x^2 + 4x + 2',
        ];

        yield [
            Polynomial::parse('x^2 + x + 1'),
            Polynomial::parse('-1x^2 + 1'),
            '-x^4 + -x^3 + x + 1',
        ];
    }

    /**
     * @dataProvider dataDiv
     */
    public function testDiv(Polynomial $p1, PolynomialInterface $p2, string $expected): void
    {
        $result = $p1->div($p2);

        $this->assertSame($expected, (string) $p1);
        $this->assertSame($expected, (string) $result);
    }

    public static function dataDiv(): \Generator
    {
        yield [
            Polynomial::parse('2.x^3 + 2*x^2 + 2'),
            Polynomial::parse('x^2 + x + 1'),
            '2x',
        ];

        yield [
            Polynomial::parse('x^2 + x + 1'),
            Polynomial::parse('-1x^2 + 1'),
            '-1',
        ];

        yield [
            Polynomial::parse('4x^4 - 3x^3 + 2*x^2 + x - 5'),
            Polynomial::parse('x^2 - 3'),
            '4x^2 + -3x + 14',
        ];
    }
}
