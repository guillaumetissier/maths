<?php

namespace Guillaumetissier\Maths;

interface Divisible
{
    public function div(self $other): static;
}
