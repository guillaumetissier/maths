<?php

namespace Guillaumetissier\Maths\Number\Decimal;

use Guillaumetissier\Maths\Exceptions\ConversionException;
use Guillaumetissier\Maths\Exceptions\InvalidTypeException;
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

    final protected function __construct(int $value, int $scale)
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
            return new static(intval($value), 0);
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

    /**
     * @throws InvalidTypeException
     */
    public function compare(ComparableNumber $other): int
    {
        if ($other instanceof IntegerInterface) {
            return $this->compareDecimals($this, $other->toDecimal());
        }

        if ($other instanceof DecimalInterface) {
            return $this->compareDecimals($this, $other);
        }

        if ($other instanceof RationalInterface) {
            return $this->compareRationals($this->toRational(), $other);
        }

        if ($other instanceof RealInterface) {
            return $this->compareReals($this->toReal(), $other);
        }

        throw InvalidTypeException::cannotBeComparedTo($other);
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

    /**
     * @return int[]
     */
    protected function reduceDecimal(int $value, int $scale): array
    {
        while ($scale > 0 && 0 === $value % 10) {
            $value /= 10;
            --$scale;
        }

        return [$value, $scale];
    }

    /**
     * @return array{int, int}
     */
    protected function addition(DecimalInterface $d): array
    {
        $maxScale = max($this->scale(), $d->scale());

        return [
            $this->value() * 10 ** ($maxScale - $this->scale()) + $d->value() * (10 ** ($maxScale - $d->scale())),
            $maxScale,
        ];
    }

    /**
     * @return array{int, int}
     */
    protected function substraction(DecimalInterface $d): array
    {
        $maxScale = max($this->scale(), $d->scale());

        return [
            $this->value() * 10 ** ($maxScale - $this->scale()) - $d->value() * (10 ** ($maxScale - $d->scale())),
            $maxScale,
        ];
    }

    /**
     * @return array{int, int}
     */
    protected function multiplication(DecimalInterface $d): array
    {
        return [
            $this->value * $d->value(),
            $this->scale + $d->scale(),
        ];
    }

    /**
     * @return array{int, int}
     */
    protected function division(RationalInterface $r): array
    {
        $result = $this->toRational()->div($r)->toDecimal();

        return [$result->value(), $result->scale()];
    }
}
