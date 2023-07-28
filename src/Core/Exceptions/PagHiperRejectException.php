<?php

namespace DevAjMeireles\PagHiper\Core\Exceptions;

use Exception;

class PagHiperRejectException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct("PagHiper Request Rejection: $message");
    }
}
