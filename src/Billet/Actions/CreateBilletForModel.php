<?php

namespace DevAjMeireles\PagHiper\Billet\Actions;

use Illuminate\Database\Eloquent\Model;

class CreateBilletForModel
{
    protected Model $model;

    public function for(Model $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function with(array $address, array $items)
    {
        return CreateBillet::execute();
    }
}
