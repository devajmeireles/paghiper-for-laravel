<?php

namespace DevAjMeireles\PagHiper\Core\Exceptions;

use Exception;

class UnallowedCastType extends Exception
{
    public function __construct(string $cast)
    {
        parent::__construct("The cast: $cast cannot be used in this context. Please, review the docs");
    }
}
