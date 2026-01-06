<?php

namespace Guillaumetissier\Maths\Number\Real;

use Guillaumetissier\Maths\Exceptions\NotYetImplementedException;
use Guillaumetissier\Maths\Number\ComparableNumber;
use Guillaumetissier\Maths\Number\CompareTrait;
use Guillaumetissier\Maths\Number\Decimal\DecimalImmutable;
use Guillaumetissier\Maths\Number\Decimal\DecimalInterface;
use Guillaumetissier\Maths\Number\Integer\IntegerImmutable;
use Guillaumetissier\Maths\Number\Integer\IntegerInterface;
use Guillaumetissier\Maths\Number\Rational\RationalImmutable;
use Guillaumetissier\Maths\Number\Rational\RationalInterface;
use Guillaumetissier\Maths\StringParsable;

abstract class AbstractReal implements RealInterface, \JsonSerializable, \Stringable, StringParsable
{
    use CompareTrait;

    public static function parse(string $value): static
    {
        // TODO: Implement parse() method.
        throw new NotYetImplementedException('AbstractReal::parse');
    }

    public function compare(ComparableNumber $other): int
    {
        return match (true) {
            $other instanceof IntegerInterface,
            $other instanceof DecimalInterface,
            $other instanceof RationalInterface => $this->compareReals($this, $other->toReal()),
            $other instanceof RealInterface => $this->compareReals($this, $other),
        };
    }

    public function toInteger(): IntegerImmutable
    {
        // TODO: Implement toInteger() method.
        throw new NotYetImplementedException('AbstractReal::toInteger');
    }

    public function toDecimal(): DecimalImmutable
    {
        // TODO: Implement toDecimal() method.
        throw new NotYetImplementedException('AbstractReal::toDecimal');
    }

    public function toRational(): RationalImmutable
    {
        // TODO: Implement toRational() method.
        throw new NotYetImplementedException('AbstractReal::toRational');
    }

    public function toReal(): RealImmutable
    {
        // TODO: Implement toReal() method.
        throw new NotYetImplementedException('AbstractReal::toReal');
    }

    public function __toString(): string
    {
        // TODO: Implement __toString() method.
        throw new NotYetImplementedException('AbstractReal::__toString');
    }

    public function jsonSerialize(): string
    {
        return (string) $this;
    }
}
