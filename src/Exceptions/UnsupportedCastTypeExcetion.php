<?php

namespace DevAjMeireles\PagHiper\Exceptions;

use Exception;

class UnsupportedCastTypeExcetion extends Exception
{
    public function __construct(string $cast)
    {
        parent::__construct("The cast: $cast is not supported. Please, review the docs.");
    }
}
