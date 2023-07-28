<?php

namespace DevAjMeireles\PagHiper\Billet;

use DevAjMeireles\PagHiper\Billet\Actions\CreateBillet;
use DevAjMeireles\PagHiper\Core\Exceptions\ResponseCastNotAllowed;
use DevAjMeireles\PagHiper\Core\Traits\InteractWithCasts;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;

class Billet
{
    use InteractWithCasts;

    private const RESULTS = ['response', 'json', 'collect'];

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

    public function create(?array $data = [], string $return = 'json'): Response|Collection|array
    {
        if (empty($data)) {
            //return new CreateBilletForModel();
        }

        $this->response = CreateBillet::execute($data);

        return $this->cast();
    }

    public function consult(string $transaction)
    {

    }

    public function cancel(string $transaction)
    {

    }
}
