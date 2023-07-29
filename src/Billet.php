<?php

namespace DevAjMeireles\PagHiper;

use DevAjMeireles\PagHiper\Actions\Billet\{CreateBillet, StatusBillet};
use DevAjMeireles\PagHiper\Actions\{Billet\CancelBillet};
use DevAjMeireles\PagHiper\DTO\Objects\{Address, Basic};
use DevAjMeireles\PagHiper\DTO\Objects\{Item};
use DevAjMeireles\PagHiper\DTO\Objects\{Payer};
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
