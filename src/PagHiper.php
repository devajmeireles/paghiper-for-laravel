<?php

namespace DevAjMeireles\PagHiper;

use DevAjMeireles\PagHiper\Enums\Cast;
use DevAjMeireles\PagHiper\Exceptions\UnallowedCastTypeException;
use DevAjMeireles\PagHiper\Resolvers\Billet\{ResolveBilletNotificationUrl};
use DevAjMeireles\PagHiper\Resolvers\{ResolveToken, ResolverApi};

class PagHiper
{
    protected ?Cast $cast = null;

    public function cast(Cast $cast = Cast::Array): self
    {
        $this->cast = $cast;

        return $this;
    }

    public function billet(Cast $cast = Cast::Array): Billet
    {
        $cast = $this->cast ?? $cast;

        if ($cast === Cast::Dto) {
            throw new UnallowedCastTypeException("dto");
        }

        return new Billet($this->cast ?? $cast);
    }

    public function notification(string $notification, string $transaction): Notification
    {
        $this->cast ??= Cast::Array;

        return new Notification($notification, $transaction, $this->cast);
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
