<?php

namespace DevAjMeireles\PagHiper;

use DevAjMeireles\PagHiper\Actions\Billet\{CancelBillet, CreateBillet, StatusBillet};
use DevAjMeireles\PagHiper\DTO\Objects\{Billet\Basic, Billet\Item, Billet\Payer};
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
}
