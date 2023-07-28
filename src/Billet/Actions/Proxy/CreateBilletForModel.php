<?php

namespace DevAjMeireles\PagHiper\Billet\Actions\Proxy;

use DevAjMeireles\PagHiper\Billet\Actions\CreateBillet;
use DevAjMeireles\PagHiper\Core\Exceptions\PagHiperRejectException;
use Illuminate\Database\Eloquent\Model;

class CreateBilletForModel
{
    protected Model $model;

    public function for(Model $model): self
    {
        $this->model = $model;

        return $this;
    }

    /** @throws PagHiperRejectException */
    public function with(array $address, array $items)
    {
        return CreateBillet::execute(collect($this->model->toArray())->merge([$address, $items])->toArray());
    }
}
