<?php

namespace DevAjMeireles\PagHiper\Traits;

use DevAjMeireles\PagHiper\Enums\Cast;

trait ShareableBaseConstructor
{
    public function __construct(
        private readonly Cast $cast = Cast::Array,
    ) {
        //
    }
}
