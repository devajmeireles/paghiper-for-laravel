<?php

namespace DevAjMeireles\PagHiper\Facades;

use DevAjMeireles\PagHiper\Billet;
use DevAjMeireles\PagHiper\Enums\Cast;
use DevAjMeireles\PagHiper\Pix;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Billet billet(Cast $cast = Cast::Array)
 * @method static Pix pix(Cast $cast = Cast::Array)
 * @method static void resolveApiUsing(callable $callback)
 * @method static void resolveTokenUsing(callable $callback)
 * @method static void resolveBilletNotificationlUrlUsing(callable $callback)
 * @method static void resolvePixNotificationlUrlUsing(callable $callback)
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
