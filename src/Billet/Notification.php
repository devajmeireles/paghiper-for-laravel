<?php

namespace DevAjMeireles\PagHiper\Billet;

use DevAjMeireles\PagHiper\Billet\Actions\Notifications\ConsultNotification;
use DevAjMeireles\PagHiper\Core\DTO\PagHiperNotification;
use DevAjMeireles\PagHiper\Core\Enums\Cast;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;

class Notification
{
    public function __construct(
        private readonly string $notification,
        private readonly string $transaction,
        private readonly Cast $cast = Cast::Array,
    ) {
        //
    }

    public function consult(): Response|PagHiperNotification|Collection|array
    {
        $response = ConsultNotification::execute($this->notification, $this->transaction);

        if ($this->cast === Cast::Dto) {
            return PagHiperNotification::fromResponse($response->json('status_request'));
        }

        return $this->cast->response($response, 'status_request');
    }
}
