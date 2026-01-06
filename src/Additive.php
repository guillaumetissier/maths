<?php

namespace Guillaumetissier\Maths;

interface Additive
{
    public function add(self $other): static;

    public function sub(self $other): static;
}
