<?php

namespace Guillaumetissier\Maths;

interface Multiplicative
{
    public function mul(self $other): static;
}
