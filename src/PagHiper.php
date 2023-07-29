<?php

namespace DevAjMeireles\PagHiper;

use DevAjMeireles\PagHiper\Billet\{Billet, Notification};
use DevAjMeireles\PagHiper\Core\Enums\Cast;
use DevAjMeireles\PagHiper\Core\Exceptions\UnallowedCastType;

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
            throw new UnallowedCastType("dto");
        }

        return new Billet($this->cast ?? $cast);
    }

    public function notification(string $notification, string $transaction): Notification
    {
        $this->cast ??= Cast::Array;

        return new Notification($notification, $transaction, $this->cast);
    }
}
