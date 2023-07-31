<?php

namespace DevAjMeireles\PagHiper\Exceptions;

use Exception;

class UnallowedCastTypeException extends Exception
{
    public function __construct(string $cast)
    {
        parent::__construct("The cast: $cast cannot be used in this context. Please, review the docs.");
    }
}
