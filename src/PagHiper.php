<?php

namespace DevAjMeireles\PagHiper;

use DevAjMeireles\PagHiper\Enums\Cast;
use DevAjMeireles\PagHiper\Resolvers\Billet\{ResolveBilletNotificationUrl};
use DevAjMeireles\PagHiper\Resolvers\{Pix\ResolvePixNotificationUrl, ResolveToken, ResolverApi};

class PagHiper
{
    public function billet(Cast $cast = Cast::Array): Billet
    {
        return new Billet($cast);
    }

    public function pix(Cast $cast = Cast::Array): Pix
    {
        return new Pix($cast);
    }

    public static function resolveApiUsing(callable $callback): void
    {
        app()->singleton(ResolverApi::class, fn () => new ResolverApi($callback));
    }

    public static function resolveTokenUsing(callable $callback): void
    {
        app()->singleton(ResolveToken::class, fn () => new ResolveToken($callback));
    }

    public static function resolveBilletNotificationlUrlUsing(callable $callback): void
    {
        app()->singleton(ResolveBilletNotificationUrl::class, fn () => new ResolveBilletNotificationUrl($callback));
    }

    public static function resolvePixNotificationUlrUsing(callable $callback): void
    {
        app()->singleton(ResolvePixNotificationUrl::class, fn () => new ResolvePixNotificationUrl($callback));
    }
}
