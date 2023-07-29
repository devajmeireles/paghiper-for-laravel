<?php

namespace DevAjMeireles\PagHiper\Billet;

use DevAjMeireles\PagHiper\Billet\Actions\Notifications\ConsultNotification;
use DevAjMeireles\PagHiper\Core\DTO\PagHiperNotification;
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

    public function consult(): Response|PagHiperNotification|Collection|array
    {
        $response = ConsultNotification::execute($this->notification, $this->transaction);

        if ($this->cast === 'dto') {
            return PagHiperNotification::fromResponse($response->json('status_request'));
        }

        $this->response = $response;

        return $this->cast('status_request');
    }
}
