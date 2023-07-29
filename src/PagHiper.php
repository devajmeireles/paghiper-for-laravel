<?php

namespace DevAjMeireles\PagHiper;

use DevAjMeireles\PagHiper\Billet\{Billet, Notification};
use DevAjMeireles\PagHiper\Core\Exceptions\UnauthorizedCastResponseException;

class PagHiper
{
    protected ?string $cast = null;

    public function cast(string $cast = 'json'): self
    {
        $this->cast = $cast;

        return $this;
    }

    public function billet(string $cast = 'json'): Billet
    {
        $cast = $this->cast ?? $cast;

        $this->castable($cast);

        return new Billet($this->cast ?? $cast);
    }

    public function notification(string $notification, string $transaction): Notification
    {
        $this->cast ??= 'json';

        $this->castable($this->cast);

        return new Notification($notification, $transaction, $this->cast);
    }

    /** @throws UnauthorizedCastResponseException */
    private function castable(string $cast)
    {
        if (!in_array($cast, [
            'response',
            'json',
            'array',
            'collect',
            'collection',
        ])) {
            throw new UnauthorizedCastResponseException($cast);
        }
    }
}
