<?php

namespace DevAjMeireles\PagHiper\Core\Exceptions;

use Exception;

class BadModelPreparationException extends Exception
{
    public function __construct(string $model)
    {
        parent::__construct("The Model: $model was not set up correctly. Please, review the docs");
    }
}
