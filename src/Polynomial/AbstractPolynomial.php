<?php

namespace Guillaumetissier\Maths\Polynomial;

use Guillaumetissier\Maths\Number\Rational\RationalImmutable;
use Guillaumetissier\Maths\Number\Rational\RationalInterface;
use Guillaumetissier\Maths\StringParsable;

class AbstractPolynomial implements \Stringable, StringParsable, PolynomialInterface
{
    /**
     * @var RationalImmutable[]
     */
    protected array $coefficients;

    protected function __construct(array $coefficients)
    {
        $this->coefficients = $coefficients;
    }

    public static function parse(string $value): static
    {
        return new static(PolynomialParser::parse($value));
    }

    public function deg(): int
    {
        return max(array_keys($this->coefficients));
    }

    public function coef(int $degree): RationalInterface
    {
        return $this->coefficients[$degree] ?? RationalImmutable::zero();
    }

    public function dominantCoef(): RationalInterface
    {
        return $this->coef($this->deg());
    }

    public function __toString(): string
    {
        $display = [];
        $degree = $this->deg();
        for ($i = 0; $i <= $degree; ++$i) {
            $power = $degree - $i;

            if (!$coefficient = $this->coefficients[$power]) {
                continue;
            }

            if (0 === $coefficient->compare(RationalImmutable::zero())) {
                continue;
            }

            if (0 === $coefficient->compare(RationalImmutable::of(1))) {
                $term = (0 < $power) ? '' : '1';
            } elseif (0 === $coefficient->compare(RationalImmutable::of(-1))) {
                $term = (0 < $power) ? '-' : '-1';
            } else {
                $term = (string) $coefficient;
            }

            if (0 < $power) {
                if (1 === $power) {
                    $term .= 'x';
                } else {
                    $term .= "x^$power";
                }
            }
            $display[] = $term;
        }

        return implode(' + ', array_filter($display, static fn ($coef) => !empty($coef)));
    }

    protected function addition(PolynomialInterface $polynomial): array
    {
        $result = [];
        for ($i = 0; $i <= max($this->deg(), $polynomial->deg()); ++$i) {
            $result[$i] = $this->coef($i)->add($polynomial->coef($i));
        }

        return $result;
    }

    protected function subtraction(PolynomialInterface $polynomial): array
    {
        $result = [];
        for ($i = 0; $i <= max($this->deg(), $polynomial->deg()); ++$i) {
            $result[$i] = $this->coef($i)->sub($polynomial->coef($i));
        }

        return $result;
    }

    protected function multiplication(PolynomialInterface $multiplier): array
    {
        $result = [];
        for ($i = 0; $i <= $this->deg(); ++$i) {
            for ($j = 0; $j <= $multiplier->deg(); ++$j) {
                $m = $this->coef($i)->mul($multiplier->coef($i));
                if (isset($result[$i + $j])) {
                    $result[$i + $j] = $result[$i + $j]->add($m);
                } else {
                    $result[$i + $j] = $m;
                }
            }
        }

        return $result;
    }

    protected function division(PolynomialInterface $divisor): array
    {
        $dividendDegree = $this->deg();
        $divisorDegree = $divisor->deg();

        if ($dividendDegree < $divisorDegree) {
            return [
                'quotient' => $this->zeroPolynomial(),
                'remainder' => $this->coefficients,
            ];
        }

        $quotientDegree = $dividendDegree - $divisorDegree;
        $quotient = $this->zeroPolynomialOfDegree($quotientDegree);
        $remainder = $this->coefficients;

        for ($i = $dividendDegree; $i >= $divisorDegree; --$i) {
            $quotientCoef = $remainder[$i]->div($divisor->dominantCoef());
            $quotientIndex = $i - $divisorDegree;
            $quotient[$quotientIndex] = $quotientCoef;

            for ($j = 0; $j <= $divisorDegree; ++$j) {
                $remainder[$i - $divisorDegree + $j] = $remainder[$i - $divisorDegree + $j]->sub(
                    $quotientCoef->mul($divisor->coef($j))
                );
            }
        }

        $remainder = $this->normalizeCoefficients(
            array_slice($remainder, 0, $divisorDegree)
        );

        return [
            'quotient' => $quotient,
            'remainder' => $remainder,
        ];
    }

    private function zeroPolynomial(): array
    {
        return [RationalImmutable::zero()];
    }

    private function zeroPolynomialOfDegree(int $degree): array
    {
        return array_fill(0, $degree + 1, RationalImmutable::zero());
    }

    private function normalizeCoefficients(array $coefficients): array
    {
        if (empty($coefficients)) {
            return $this->zeroPolynomial();
        }

        while (count($coefficients) > 1 && end($coefficients)->isZero()) {
            array_pop($coefficients);
        }

        return $coefficients;
    }
}
