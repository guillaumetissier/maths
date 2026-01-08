<?php

namespace Guillaumetissier\Maths\Tests\Polynomial;

use Guillaumetissier\Maths\Exceptions\InvalidPolynomialException;
use Guillaumetissier\Maths\Number\Rational\RationalInterface;
use Guillaumetissier\Maths\Polynomial\PolynomialParser;
use PHPUnit\Framework\TestCase;

class PolynomialParserTest extends TestCase
{
    /**
     * @dataProvider dataParse
     */
    public function testParse(string $input, array $expected): void
    {
        /** @var RationalInterface[] $result */
        $result = PolynomialParser::parse($input);

        if (!empty($expected)) {
            for ($i = 0; $i < count($expected); ++$i) {
                $this->assertEquals($expected[$i], $result[$i]->numerator(), "Numerator of {$i}th element");
                $this->assertEquals(1, $result[$i]->denominator(), "Denominator of {$i}th element");
            }
        } else {
            $this->assertEmpty($result);
        }
    }

    public static function dataParse(): array
    {
        return [
            'basic polynomial' => ['2x^2 + 3x^1 + 5', [5, 3, 2]],
            'dot notation' => ['2.x^2 + 3.x^1 + 5', [5, 3, 2]],
            'double asterisk power' => ['2x**2 + 3x + 5', [5, 3, 2]],
            'without space' => ['2x^2+3x+5', [5, 3, 2]],
            'negative coefficients' => ['x^2 - 3x + 5', [5, -3, 1]],
            'implicit coefficients' => ['x^2 + x + 1', [1, 1, 1]],
            'negative implicit coefficients' => ['-x^2 + x - 1', [-1, 1, -1]],
            'empty string' => ['', []],
            'zero polynomial' => ['0', []],
            'constant only' => ['42', [42]],
            'linear' => ['3x + 7', [7, 3]],
            'highest degree' => ['6x^5 + 2x^3 - x + 1', [1, -1, 0, 2, 0, 6]],
            'capital x' => ['-X^3 +2X^2 - X + 5', [5, -1, 2, -1]],
            'repeated degrees' => ['2x^2 + 3x^2 + x', [0, 1, 5]],
            'standard form' => ['2x^2 + 3x + 5', [5, 3, 2]],
            'reverse order' => ['5 + 3x + 2x^2', [5, 3, 2]],
            'unordered terms' => ['3x + 5x^3 + 2x^2 + 3', [3, 3, 2, 5]],
            'missing middle term' => ['x^2 + 5', [5, 0, 1]],
            'just x' => ['x', [0, 1]],
            'negative leading' => ['-x^2 + 1', [1, 0, -1]],
            'cubic' => ['x^3 - x', [0, -1, 0, 1]],
            'double plus' => ['2x^2 + + 3x', [0, 3, 2]],
        ];
    }

    /**
     * @dataProvider dataParseWithRationalCoefs
     */
    public function testParseWithRationalCoefs(string $input, array $expected): void
    {
        /** @var RationalInterface[] $result */
        $result = PolynomialParser::parse($input);

        for ($i = 0; $i < count($expected); ++$i) {
            $this->assertEquals($expected[$i][0], $result[$i]->numerator());
            $this->assertEquals($expected[$i][1], $result[$i]->denominator());
        }
    }

    public static function dataParseWithRationalCoefs(): array
    {
        return [
            'decimal coefficient' => ['2.5x^2 + 1.5x - 0.5', [[-1, 2], [3, 2], [5, 2]]],
            'fractional' => ['1/7x^2 + 1/4x', [[0, 1], [1, 4], [1, 7]]],
            'fractional with ** notation' => ['1/3x**2 + 1/4x', [[0, 1],  [1, 4], [1, 3]]],
            'decimal coefs with many 0 coefs' => ['2.6x**5 + 0.0x^3 + 1', [[1, 1], [0, 1], [0, 1], [0, 1], [0, 1], [13, 5]]],
            'fractional coefs with many 0 coefs' => ['-2/6.x**5 + 0.0x^3 - 1', [[-1, 1], [0, 1], [0, 1], [0, 1], [0, 1], [-1, 3]]],
        ];
    }

    public function testInvalidCharacters(): void
    {
        $this->expectException(InvalidPolynomialException::class);
        PolynomialParser::parse('2x^2 + y + 5');
    }
}
