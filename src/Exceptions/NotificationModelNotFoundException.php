<?php

namespace DevAjMeireles\PagHiper\Exceptions;

use Exception;

class NotificationModelNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct("No model identified from the billet. Please, review the docs.");
    }
}
