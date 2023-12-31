<?php

namespace DevAjMeireles\PagHiper;

use DevAjMeireles\PagHiper\Enums\Cast;
use DevAjMeireles\PagHiper\Exceptions\UnsupportedCastTypeExcetion;
use DevAjMeireles\PagHiper\Resolvers\Billet\{ResolveBilletNotificationUrl};
use DevAjMeireles\PagHiper\Resolvers\{Pix\ResolvePixNotificationUrl, ResolveToken, ResolverApi};

class PagHiper
{
    public function billet(Cast $cast = Cast::Array): Billet
    {
        if ($cast === Cast::PixNotification) {
            throw new UnsupportedCastTypeExcetion($cast->name);
        }

        return new Billet($cast);
    }

    public function pix(Cast $cast = Cast::Array): Pix
    {
        if ($cast === Cast::BilletNotification) {
            throw new UnsupportedCastTypeExcetion($cast->name);
        }

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

    public function resolveCredentialsUsing(callable $api, callable $token): void
    {
        $this->resolveApiUsing($api);
        $this->resolveTokenUsing($token);
    }

    public function resolveBilletNotificationUrlUsing(callable $callback): void
    {
        app()->singleton(ResolveBilletNotificationUrl::class, fn () => new ResolveBilletNotificationUrl($callback));
    }

    public function resolvePixNotificationUrlUsing(callable $callback): void
    {
        app()->singleton(ResolvePixNotificationUrl::class, fn () => new ResolvePixNotificationUrl($callback));
    }

    public function resolveNotificationUrlUsing(callable $billet, callable $pix): void
    {
        $this->resolveBilletNotificationUrlUsing($billet);
        $this->resolvePixNotificationUrlUsing($pix);
    }
}
