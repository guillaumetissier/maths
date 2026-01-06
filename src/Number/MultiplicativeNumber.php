<?php

namespace Guillaumetissier\Maths\Number;

interface MultiplicativeNumber extends Number
{
    public function mul(MultiplicativeNumber $other): static;
}
