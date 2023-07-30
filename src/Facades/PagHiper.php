<?php

namespace DevAjMeireles\PagHiper\Facades;

use DevAjMeireles\PagHiper\Enums\Cast;
use DevAjMeireles\PagHiper\{Billet, Notification};
use Illuminate\Support\Facades\Facade;

/**
 * @method static self cast(Cast $cast = Cast::Array)
 * @method static Billet billet(Cast $cast = Cast::Array)
 * @method static Notification notification(string $notification, string $transaction)
 *
 * @see \DevAjMeireles\PagHiper\PagHiper
 */
class PagHiper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \DevAjMeireles\PagHiper\PagHiper::class;
    }
}
