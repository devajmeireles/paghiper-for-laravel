<?php

namespace DevAjMeireles\PagHiper\Billet;

use DevAjMeireles\PagHiper\Billet\Actions\CreateBillet;
use DevAjMeireles\PagHiper\Billet\Actions\CreateBilletForModel;

class Billet
{
    public function create(?array $data = []): CreateBilletForModel|array
    {
        if (empty($data)) {
            //return new CreateBilletForModel();
        }

        return CreateBillet::execute($data);
    }

    public function consult(string $transaction)
    {

    }

    public function cancel(string $transaction)
    {

    }
}
