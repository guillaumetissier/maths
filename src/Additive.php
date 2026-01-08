<?php

namespace Guillaumetissier\Maths;

interface Additive
{
    public function add(mixed $other): static;

    public function sub(mixed $other): static;
}
