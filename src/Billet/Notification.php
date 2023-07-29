<?php

namespace DevAjMeireles\PagHiper\Billet;

use DevAjMeireles\PagHiper\Billet\Actions\Notifications\ConsultNotification;
use DevAjMeireles\PagHiper\Core\Traits\InteractWithCasts;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;

class Notification
{
    use InteractWithCasts;

    public function __construct(
        private readonly string $notification,
        private readonly string $transaction,
        private readonly string $cast = 'json',
    ) {
        //
    }

    public function consult(): Response|Collection|array
    {
        $this->response = ConsultNotification::execute($this->notification, $this->transaction);

        return $this->cast('status_request');
    }
}
