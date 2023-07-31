<?php

namespace DevAjMeireles\PagHiper;

use DevAjMeireles\PagHiper\Enums\Cast;
use DevAjMeireles\PagHiper\Resolvers\Billet\{ResolveBilletNotificationUrl};
use DevAjMeireles\PagHiper\Resolvers\{ResolveToken, ResolverApi};

class PagHiper
{
    protected ?Cast $cast = null;

    public function billet(Cast $cast = Cast::Array): Billet
    {
        $cast = $this->cast ?? $cast;

        return new Billet($this->cast ?? $cast);
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
}
