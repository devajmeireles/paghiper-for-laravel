<?php

namespace DevAjMeireles\PagHiper\Billet;

use DevAjMeireles\PagHiper\Billet\Actions\{Billet\CancelBillet, Billet\CreateBillet, Billet\StatusBillet};
use DevAjMeireles\PagHiper\Core\DTO\Objects\{Address, Basic, Item};
use DevAjMeireles\PagHiper\Core\DTO\Objects\{Payer};
use DevAjMeireles\PagHiper\Core\Enums\Cast;
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

    public function create(Payer|Model $payer, Basic $basic, Address $address, array|Item $items): Response|Collection|array
    {
        $response = CreateBillet::execute($payer, $basic, $address, $items);

        return $this->cast->response($response, 'create_request');
    }

    public function status(string $transaction): Response|Collection|array
    {
        $response = StatusBillet::execute($transaction);

        return $this->cast->response($response, 'status_request');
    }

    public function cancel(string $transaction): Response|Collection|array
    {
        $response = CancelBillet::execute($transaction);

        return $this->cast->response($response, 'cancellation_request');
    }
}
