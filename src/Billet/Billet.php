<?php

namespace DevAjMeireles\PagHiper\Billet;

use DevAjMeireles\PagHiper\Billet\Actions\{
    CancelBillet,
    CreateBillet,
    StatusBillet
};
use DevAjMeireles\PagHiper\Core\Exceptions\{PagHiperRejectException, ResponseCastNotAllowed};
use DevAjMeireles\PagHiper\Core\Traits\InteractWithCasts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;

class Billet
{
    use InteractWithCasts;

    private const RESULTS = ['response', 'json', 'array', 'collect', 'collection'];

    /** @throws ResponseCastNotAllowed */
    public function __construct(
        private readonly string $cast = 'json'
    ) {
        if (
            !in_array($this->cast, self::RESULTS) &&
            (is_string($this->cast) && !class_exists($this->cast))
        ) {
            throw new ResponseCastNotAllowed($this->cast);
        }
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
