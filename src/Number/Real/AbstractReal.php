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

    /**
     * @throws NotYetImplementedException
     */
    public static function parse(string $value): static
    {
        // TODO: Implement parse() method.
        throw new NotYetImplementedException('AbstractReal::parse');
    }

    public function compare(ComparableNumber $other): int
    {
        if ($other instanceof IntegerInterface
            || $other instanceof DecimalInterface
            || $other instanceof RationalInterface
        ) {
            return $this->compareReals($this, $other->toReal());
        }

        return $this->compareReals($this, $other->toReal());
    }

    /**
     * @throws NotYetImplementedException
     */
    public function toInteger(): IntegerImmutable
    {
        // TODO: Implement toInteger() method.
        throw new NotYetImplementedException('AbstractReal::toInteger');
    }

    /**
     * @throws NotYetImplementedException
     */
    public function toDecimal(): DecimalImmutable
    {
        // TODO: Implement toDecimal() method.
        throw new NotYetImplementedException('AbstractReal::toDecimal');
    }

    /**
     * @throws NotYetImplementedException
     */
    public function toRational(): RationalImmutable
    {
        // TODO: Implement toRational() method.
        throw new NotYetImplementedException('AbstractReal::toRational');
    }

    /**
     * @throws NotYetImplementedException
     */
    public function toReal(): RealImmutable
    {
        // TODO: Implement toReal() method.
        throw new NotYetImplementedException('AbstractReal::toReal');
    }

    /**
     * @throws NotYetImplementedException
     */
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
