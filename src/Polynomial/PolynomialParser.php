<?php

declare(strict_types=1);

namespace Guillaumetissier\Maths\Polynomial;

use Guillaumetissier\Maths\Exceptions\InvalidPolynomialException;
use Guillaumetissier\Maths\Number\Rational\RationalImmutable;

class PolynomialParser
{
    /**
     * Parse une chaîne représentant un polynôme en tableau de coefficients.
     *
     * @param string $string Polynôme sous forme de chaîne (ex: "2x^2 + 3x - 5")
     *
     * @return array Coefficients indexés par degré [a0, a1, a2, ...] où P(x) = a0 + a1*x + a2*x² + ...
     *
     * @throws InvalidPolynomialException Si la syntaxe est invalide
     */
    public static function parse(string $string): array
    {
        if (empty($string)) {
            return [];
        }

        $result = [];
        $coefficients = [];
        $string = self::normalize($string);
        $terms = self::extractTerms($string);

        /* @var RationalImmutable[] $coefficients */
        foreach ($terms as $term) {
            ['coefficient' => $coef, 'degree' => $deg] = self::parseTerm($term);

            if (isset($coefficients[$deg])) {
                $coefficients[$deg] = $coefficients[$deg]->add($coef);
            } else {
                $coefficients[$deg] = $coef;
            }
        }

        $maxDegree = empty($coefficients) ? 0 : max(array_keys($coefficients));

        for ($i = 0; $i <= $maxDegree; ++$i) {
            $result[$i] = $coefficients[$i] ?? RationalImmutable::of(0);
        }

        return $result;
    }

    private static function normalize(string $string): string
    {
        $string = preg_replace('/\s+/', '', $string);
        $string = str_replace([',', '**', '-', '++', 'X', '-x'], ['.', '^', '+-', '+', 'x', '-1x'], $string);

        return ltrim($string, '+');
    }

    private static function extractTerms(string $string): array
    {
        if (empty($string)) {
            return [];
        }
        $terms = explode('+', $string);

        return array_filter($terms, fn ($term) => '' !== $term);
    }

    /**
     * @throws InvalidPolynomialException
     */
    private static function parseTerm(string $term): array
    {
        // Pattern to capture coefficient and degree from term: [coefficient][x][^degree]
        // example : -3.4*x^2
        // 0 => -3.4x^2
        // 1 => -3.4 => coef
        // 2 => 3.4
        // 3 => .4
        // 4 => *
        // 5 => x
        // 6 => 2 => degree
        $pattern = '#^(-?(\d+([./]\d+)?))?([.*])?([xX])?(?:\^(-?\d+))?$#';

        if (!preg_match($pattern, $term, $matches)) {
            throw new InvalidPolynomialException("Terme invalide: '$term'");
        }

        $coefficient = null;
        if (!empty($matches[1])) {
            $coefficient = RationalImmutable::parse($matches[1]);
        } elseif (isset($matches[2])) {
            $coefficient = RationalImmutable::of('-' === $term[0] ? -1 : 1);
        }

        $degree = 0;
        if (!empty($matches[5])) {
            if (!empty($matches[6])) {
                $degree = (int) $matches[6];
            } else {
                $degree = 1;
            }
        }

        return [
            'coefficient' => $coefficient,
            'degree' => $degree,
        ];
    }
}
