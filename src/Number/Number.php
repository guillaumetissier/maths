<?php

namespace Guillaumetissier\Maths\Number;

use Guillaumetissier\Maths\Exceptions\ConversionException;
use Guillaumetissier\Maths\Number\Decimal\DecimalImmutable;
use Guillaumetissier\Maths\Number\Integer\IntegerImmutable;
use Guillaumetissier\Maths\Number\Rational\RationalImmutable;
use Guillaumetissier\Maths\Number\Real\RealImmutable;

interface Number
{
    /**
     * @throws ConversionException if conversion impossible
     */
    public function toInteger(): IntegerImmutable;

    /**
     * @throws ConversionException if conversion impossible
     */
    public function toDecimal(): DecimalImmutable;

    /**
     * @throws ConversionException if conversion impossible
     */
    public function toRational(): RationalImmutable;

    public function toReal(): RealImmutable;
}
