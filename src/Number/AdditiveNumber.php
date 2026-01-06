<?php

namespace Guillaumetissier\Maths\Number;

interface AdditiveNumber extends Number
{
    public function add(AdditiveNumber $other): static;

    public function sub(AdditiveNumber $other): static;
}
