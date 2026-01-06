<?php

namespace Guillaumetissier\Maths\Number\Decimal;

use Guillaumetissier\Maths\Exceptions\ConversionException;
use Guillaumetissier\Maths\Number\ComparableNumber;
use Guillaumetissier\Maths\Number\CompareTrait;
use Guillaumetissier\Maths\Number\Integer\IntegerImmutable;
use Guillaumetissier\Maths\Number\Integer\IntegerInterface;
use Guillaumetissier\Maths\Number\Rational\RationalImmutable;
use Guillaumetissier\Maths\Number\Rational\RationalInterface;
use Guillaumetissier\Maths\Number\Real\RealImmutable;
use Guillaumetissier\Maths\Number\Real\RealInterface;
use Guillaumetissier\Maths\StringParsable;

abstract class AbstractDecimal implements DecimalInterface, \JsonSerializable, \Stringable, StringParsable
{
    use CompareTrait;

    protected int $value;

    protected int $scale;

    protected function __construct(int $value, int $scale)
    {
        [$this->value, $this->scale] = $this->reduceDecimal($value, $scale);
    }

    public static function of(int $value, int $scale = 0): static
    {
        return new static($value, $scale);
    }

    public static function parse(string $value): static
    {
        $length = strlen($value);
        $pointPosition = strpos($value, '.');

        if (false === $pointPosition) {
            return new static($value, 0);
        }

        return new static(intval(str_replace('.', '', $value)), $length - $pointPosition - 1);
    }

    public function value(): int
    {
        return $this->value;
    }

    public function scale(): int
    {
        return $this->scale;
    }

    public function val(): float
    {
        return floatval((string) $this);
    }

    public function compare(ComparableNumber $other): int
    {
        return match (true) {
            $other instanceof IntegerInterface => $this->compareDecimals($this, $other->toDecimal()),
            $other instanceof DecimalInterface => $this->compareDecimals($this, $other),
            $other instanceof RationalInterface => $this->compareRationals($this->toRational(), $other),
            $other instanceof RealInterface => $this->compareReals($this->toReal(), $other),
        };
    }

    public function toInteger(): IntegerImmutable
    {
        if (0 !== $this->scale) {
            throw new ConversionException('int');
        }

        return IntegerImmutable::of($this->value);
    }

    public function toDecimal(): DecimalImmutable
    {
        return new DecimalImmutable($this->value, $this->scale);
    }

    public function toRational(): RationalImmutable
    {
        return RationalImmutable::of($this->value, 10 ** $this->scale);
    }

    public function toReal(): RealImmutable
    {
        return RealImmutable::parse((string) $this);
    }

    public function __toString(): string
    {
        if (0 === $this->scale) {
            return (string) $this->value;
        }

        $sign = $this->value < 0 ? '-' : '';
        $digits = (string) abs($this->value);
        $len = strlen($digits);

        if ($len <= $this->scale) {
            return $sign.'0.'.str_repeat('0', $this->scale - $len).$digits;
        }

        $intPart = substr($digits, 0, $len - $this->scale);
        $fracPart = substr($digits, $len - $this->scale);

        return $sign.$intPart.'.'.$fracPart;
    }

    public function jsonSerialize(): string
    {
        return (string) $this;
    }

    protected function reduceDecimal(int $value, int $scale): array
    {
        while ($scale > 0 && 0 === $value % 10) {
            $value /= 10;
            --$scale;
        }

        return [$value, $scale];
    }

    protected function addition(DecimalInterface $d): array
    {
        $maxScale = max($this->scale(), $d->scale());

        return [
            $this->value() * 10 ** ($maxScale - $this->scale()) + $d->value() * (10 ** ($maxScale - $d->scale())),
            $maxScale,
        ];
    }

    protected function substraction(DecimalInterface $d): array
    {
        $maxScale = max($this->scale(), $d->scale());

        return [
            $this->value() * 10 ** ($maxScale - $this->scale()) - $d->value() * (10 ** ($maxScale - $d->scale())),
            $maxScale,
        ];
    }

    protected function multiplication(DecimalInterface $d): array
    {
        return [
            $this->value * $d->value(),
            $this->scale + $d->scale(),
        ];
    }

    protected function division(RationalInterface $r): array
    {
        $result = $this->toRational()->div($r)->toDecimal();
        //        $powers = [2 => 0, 5 => 0];
        //        $numerator = $r->numerator();
        //        $denominator = $r->denominator();
        //
        //        foreach (array_keys($powers) as $divisor) {
        //            while (0 === $denominator % $divisor) {
        //                $denominator /= $divisor;
        //                ++$powers[$divisor];
        //            }
        //        }
        //
        //        if (1 !== $denominator) {
        //            throw new ConversionException('Decimal division produces a non-terminating decimal');
        //        }
        //
        //        $scale = max($powers);
        //        $numerator *= (2 ** ($scale - $powers[2])) * (5 ** ($scale - $powers[5]));

        return [$result->value(), $result->scale()];
    }
}
