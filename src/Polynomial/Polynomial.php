<?php

declare(strict_types=1);

namespace Guillaumetissier\Maths\Polynomial;

class Polynomial extends AbstractPolynomial
{
    public function add(PolynomialInterface $polynomial): Polynomial
    {
        $this->coefficients = $this->addition($polynomial);

        return $this;
    }

    public function sub(PolynomialInterface $polynomial): Polynomial
    {
        $this->coefficients = $this->subtraction($polynomial);

        return $this;
    }

    public function mul(PolynomialInterface $polynomial): Polynomial
    {
        $this->coefficients = $this->multiplication($polynomial);

        return $this;
    }

    public function div(PolynomialInterface $polynomial): Polynomial
    {
        $this->coefficients = $this->division($polynomial)['quotient'];

        return $this;
    }

    public function mod(PolynomialInterface $polynomial): Polynomial
    {
        $this->coefficients = $this->division($polynomial)['remainder'];

        return $this;
    }
}
