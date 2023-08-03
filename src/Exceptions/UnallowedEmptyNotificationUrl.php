<?php

namespace DevAjMeireles\PagHiper\Exceptions;

use Exception;

class UnallowedEmptyNotificationUrl extends Exception
{
    public function __construct()
    {
        parent::__construct("Attempt to interact with PagHiper without notification URL to the PagHiper callbacks. Please, review the docs.");
    }
}
