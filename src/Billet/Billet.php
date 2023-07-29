<?php

namespace DevAjMeireles\PagHiper\Billet;

use DevAjMeireles\PagHiper\Billet\Actions\{Billet\CancelBillet, Billet\CreateBillet, Billet\StatusBillet};
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

    public function create(array|Model $data, ...$parameters): Response|Collection|array
    {
        $this->response = CreateBillet::execute($data, $parameters);

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
