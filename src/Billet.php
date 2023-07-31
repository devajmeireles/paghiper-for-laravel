<?php

namespace DevAjMeireles\PagHiper;

use DevAjMeireles\PagHiper\Actions\Billet\{CancelBillet, CreateBillet, NotificationBillet, StatusBillet};
use DevAjMeireles\PagHiper\DTO\Objects\{Billet\Basic, Billet\Item, Billet\Payer};
use DevAjMeireles\PagHiper\DTO\PagHiperNotification;
use DevAjMeireles\PagHiper\Enums\Cast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;

class Billet
{
    public function __construct(
        private readonly Cast $cast = Cast::Array,
    ) {
        //
    }

    public function create(Basic $basic, Payer|Model $payer, array|Item $items): Response|Collection|array|string
    {
        $response = CreateBillet::execute($basic, $payer, $items);

        return $this->cast->response($response, 'create_request');
    }

    public function status(string $transaction): Response|Collection|array|string
    {
        $response = StatusBillet::execute($transaction);

        return $this->cast->response($response, 'status_request');
    }

    public function cancel(string $transaction): Response|Collection|array|string
    {
        $response = CancelBillet::execute($transaction);

        return $this->cast->response($response, 'cancellation_request');
    }

    public function notification(string $notification, string $transaction): PagHiperNotification|Response|Collection|array|string
    {
        $response = NotificationBillet::execute($notification, $transaction);

        if ($this->cast === Cast::BilletNotification) {
            return PagHiperNotification::make($response);
        }

        return $this->cast->response($response, 'status_request');
    }
}
