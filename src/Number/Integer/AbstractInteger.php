<?php

declare(strict_types=1);

namespace Guillaumetissier\Maths\Number\Integer;

use Guillaumetissier\Maths\Exceptions\InvalidTypeException;
use Guillaumetissier\Maths\Exceptions\NotYetImplementedException;
use Guillaumetissier\Maths\Number\ComparableNumber;
use Guillaumetissier\Maths\Number\CompareTrait;
use Guillaumetissier\Maths\Number\Decimal\DecimalImmutable;
use Guillaumetissier\Maths\Number\Decimal\DecimalInterface;
use Guillaumetissier\Maths\Number\Rational\RationalImmutable;
use Guillaumetissier\Maths\Number\Rational\RationalInterface;
use Guillaumetissier\Maths\Number\Real\RealImmutable;
use Guillaumetissier\Maths\Number\Real\RealInterface;
use Guillaumetissier\Maths\StringParsable;

abstract class AbstractInteger implements IntegerInterface, \JsonSerializable, StringParsable, \Stringable
{
    use CompareTrait;

    final protected function __construct(protected int $value)
    {
    }

    public static function of(int $integer): static
    {
        return new static($integer);
    }

    public static function parse(string $value): static
    {
        $value = trim($value);

        if (preg_match('#^-?\d+$#', $value)) {
            return new static((int) $value);
        }

        throw new \InvalidArgumentException(sprintf('Invalid integer string "%s".', $value));
    }

    public function val(): int
    {
        return $this->value;
    }

    /**
     * @throws InvalidTypeException
     * @throws NotYetImplementedException
     */
    public function compare(ComparableNumber $other): int
    {
        if ($other instanceof IntegerInterface) {
            return $this->compareIntegers($this, $other->toInteger());
        }

        if ($other instanceof DecimalInterface) {
            return $this->compareDecimals($this->toDecimal(), $other);
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
        return new IntegerImmutable($this->value);
    }

    public function toDecimal(): DecimalImmutable
    {
        return DecimalImmutable::of($this->value);
    }

    public function toRational(): RationalImmutable
    {
        return RationalImmutable::of($this->value);
    }

    /**
     * @throws NotYetImplementedException
     */
    public function toReal(): RealImmutable
    {
        return RealImmutable::parse((string) $this);
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    public function jsonSerialize(): string
    {
        return (string) $this->value;
    }
}
