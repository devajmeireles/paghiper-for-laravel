<?php

namespace DevAjMeireles\PagHiper\Core\Exceptions;

use Exception;

class ResponseCastNotAllowed extends Exception
{
    public function __construct(string $cast)
    {
        parent::__construct("The response cast: $cast is not allowed");
    }
}
