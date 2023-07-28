<?php

namespace DevAjMeireles\PagHiper\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \DevAjMeireles\PagHiper\PagHiper
 */
class PagHiper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \DevAjMeireles\PagHiper\PagHiper::class;
    }
}