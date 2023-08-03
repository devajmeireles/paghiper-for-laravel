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

    public function resolveApiUsing(callable $callback): void
    {
        app()->singleton(ResolverApi::class, fn () => new ResolverApi($callback));
    }

    public function resolveTokenUsing(callable $callback): void
    {
        app()->singleton(ResolveToken::class, fn () => new ResolveToken($callback));
    }

    public function resolveBilletNotificationUrlUsing(callable $callback): void
    {
        app()->singleton(ResolveBilletNotificationUrl::class, fn () => new ResolveBilletNotificationUrl($callback));
    }

    public function resolvePixNotificationUrlUsing(callable $callback): void
    {
        app()->singleton(ResolvePixNotificationUrl::class, fn () => new ResolvePixNotificationUrl($callback));
    }
}
