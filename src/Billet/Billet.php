<?php

namespace DevAjMeireles\PagHiper\Billet;

use DevAjMeireles\PagHiper\Billet\Actions\{Billet\CancelBillet, Billet\CreateBillet, Billet\StatusBillet};
use DevAjMeireles\PagHiper\Core\DTO\Objects\{Address, Basic, Item};
use DevAjMeireles\PagHiper\Core\DTO\Objects\{Payer};
use DevAjMeireles\PagHiper\Core\Traits\InteractWithCasts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;

class Billet
{
    use InteractWithCasts;

    public function __construct(
        private readonly string $cast = 'json'
    ) {
        //
    }

    public function create(Payer|Model $payer, Basic $basic, Address $address, array|Item $items): Response|Collection|array
    {
        $this->response = CreateBillet::execute($payer, $basic, $address, $items);

        return $this->cast('create_request');
    }

    public function status(string $transaction): Response|Collection|array
    {
        $this->response = StatusBillet::execute($transaction);

        return $this->cast('status_request');
    }

    public function cancel(string $transaction): Response|Collection|array
    {
        $this->response = CancelBillet::execute($transaction);

        return $this->cast('cancellation_request');
    }
}
