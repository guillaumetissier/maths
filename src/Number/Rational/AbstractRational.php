<?php

declare(strict_types=1);

namespace Guillaumetissier\Maths\Number\Rational;

use Guillaumetissier\Maths\Number\ComparableNumber;
use Guillaumetissier\Maths\Number\CompareTrait;
use Guillaumetissier\Maths\Number\Decimal\DecimalImmutable;
use Guillaumetissier\Maths\Number\Decimal\DecimalInterface;
use Guillaumetissier\Maths\Number\Integer\IntegerImmutable;
use Guillaumetissier\Maths\Number\Integer\IntegerInterface;
use Guillaumetissier\Maths\Number\Real\RealImmutable;
use Guillaumetissier\Maths\Number\Real\RealInterface;
use Guillaumetissier\Maths\StringParsable;

/**
 * rational number (fraction).
 *
 * Represents a number as numerator / denominator,
 * always kept in reduced form with a positive denominator.
 */
abstract class AbstractRational implements RationalInterface, \JsonSerializable, \Stringable, StringParsable
{
    use CompareTrait;

    protected int $numerator;

    protected int $denominator;

    protected function __construct(int $numerator, int $denominator)
    {
        if (0 === $denominator) {
            throw new \InvalidArgumentException('Denominator cannot be zero.');
        }

        $this->canonicalize($numerator, $denominator);
    }

    public static function of(int $numerator, int $denominator = 1): static
    {
        return new static($numerator, $denominator);
    }

    public static function parse(string $value): static
    {
        $value = trim($value);

        if (preg_match('#^(-?\d+)\s*/\s*(-?\d+)$#', $value, $matches)) {
            return new static((int) $matches[1], (int) $matches[2]);
        }

        if (preg_match('#^-?\d+$#', $value)) {
            return new static((int) $value, 1);
        }

        throw new \InvalidArgumentException(sprintf('Invalid rational string "%s".', $value));
    }

    public static function fromFloat(float $value, int $precision = 10): static
    {
        if (!is_finite($value)) {
            throw new \InvalidArgumentException('Float value must be finite.');
        }

        $factor = 10 ** $precision;
        $numerator = (int) round($value * $factor);

        return new static($numerator, $factor);
    }

    public function numerator(): int
    {
        return $this->numerator;
    }

    public function denominator(): int
    {
        return $this->denominator;
    }

    public function compare(ComparableNumber $other): int
    {
        return match (true) {
            $other instanceof IntegerInterface,
            $other instanceof DecimalInterface => $this->compareRationals($this, $other->toRational()),
            $other instanceof RationalInterface => $this->compareRationals($this, $other),
            $other instanceof RealInterface => $this->compareReals($this->toReal(), $other),
        };
    }

    public function toInteger(): IntegerImmutable
    {
        if (1 !== $this->denominator) {
            throw new \LogicException('Cannot be converted to integer.');
        }

        return IntegerImmutable::of($this->numerator);
    }

    public function toDecimal(): DecimalImmutable
    {
        if (!$this->isDecimal()) {
            throw new \LogicException('Cannot be converted to decimal.');
        }

        $num = $this->numerator;
        $den = $this->denominator;
        $scale2 = 0;
        $scale5 = 0;
        while (0 === $den % 2) {
            $den /= 2;
            ++$scale2;
        }
        while (0 === $den % 5) {
            $den /= 5;
            ++$scale5;
        }
        $scale = max($scale2, $scale5);
        $multiplier = (10 ** $scale) / (($scale2 ? 2 ** $scale2 : 1) * ($scale5 ? 5 ** $scale5 : 1));
        $value = $num * $multiplier;

        return DecimalImmutable::of($value, $scale);
    }

    public function toRational(): RationalImmutable
    {
        return new RationalImmutable($this->numerator, $this->denominator);
    }

    public function toReal(): RealImmutable
    {
        // TODO: Implement toReal() method.
        return RealImmutable::parse((string) $this);
    }

    public function isDecimal(): bool
    {
        $den = $this->denominator;
        while (0 === $den % 2) {
            $den /= 2;
        }
        while (0 === $den % 5) {
            $den /= 5;
        }

        return 1 === $den;
    }

    public function toInt(): int
    {
        if (1 !== $this->denominator) {
            throw new \LogicException('Cannot be converted to integer.');
        }

        return $this->numerator;
    }

    public function toFloat(): float
    {
        return floatval($this->numerator / $this->denominator);
    }

    public function __toString(): string
    {
        if (1 === $this->denominator) {
            return (string) $this->numerator;
        }

        return $this->numerator.'/'.$this->denominator;
    }

    public function jsonSerialize(): string
    {
        return (string) $this;
    }

    protected function canonicalize(int $numerator, int $denominator): static
    {
        if ($denominator < 0) {
            $numerator = -$numerator;
            $denominator = -$denominator;
        }

        $gcd = self::gcd(abs($numerator), $denominator);

        $this->numerator = intdiv($numerator, $gcd);
        $this->denominator = intdiv($denominator, $gcd);

        return $this;
    }

    private static function gcd(int $a, int $b): int
    {
        while (0 !== $b) {
            [$a, $b] = [$b, $a % $b];
        }

        return $a;
    }
}
