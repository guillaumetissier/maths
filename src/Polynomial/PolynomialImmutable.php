<?php

namespace Guillaumetissier\Maths\Polynomial;

class PolynomialImmutable extends AbstractPolynomial
{
    public function add(PolynomialInterface $polynomial): PolynomialImmutable
    {
        return new PolynomialImmutable($this->addition($polynomial));
    }

    public function sub(PolynomialInterface $polynomial): PolynomialImmutable
    {
        return new PolynomialImmutable($this->subtraction($polynomial));
    }

    public function mul(PolynomialInterface $polynomial): PolynomialImmutable
    {
        return new PolynomialImmutable($this->multiplication($polynomial));
    }

    public function div(PolynomialInterface $polynomial): PolynomialImmutable
    {
        return new PolynomialImmutable($this->division($polynomial)['quotient']);
    }

    public function mod(PolynomialInterface $polynomial): PolynomialImmutable
    {
        return new PolynomialImmutable($this->division($polynomial)['remainder']);
    }
}
