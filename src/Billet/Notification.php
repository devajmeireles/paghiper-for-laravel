<?php

namespace DevAjMeireles\PagHiper\Billet;

use DevAjMeireles\PagHiper\Billet\Actions\Notifications\VerifyNotification;
use DevAjMeireles\PagHiper\Core\Traits\InteractWithCasts;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;

class Notification
{
    use InteractWithCasts;

    public function __construct(
        private readonly string $notification,
        private readonly string $transaction,
    ) {
        //
    }

    public function verify(): Response|Collection|array
    {
        $this->response = VerifyNotification::execute($this->notification,$this->transaction);

        return $this->cast('status_request');
    }
}
