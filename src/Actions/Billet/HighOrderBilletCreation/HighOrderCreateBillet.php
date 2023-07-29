<?php

namespace DevAjMeireles\PagHiper\Actions\Billet\HighOrderBilletCreation;

use DevAjMeireles\PagHiper\Contracts\PagHiperModelAbstraction;
use DevAjMeireles\PagHiper\Exceptions\WrongModelSetUpException;
use Illuminate\Database\Eloquent\Model;

class HighOrderCreateBillet
{
    public function __construct(
        protected readonly Model $model
    ) {
    }

    /** @throws WrongModelSetUpException */
    public function execute(): array
    {
        if (!$this->model instanceof PagHiperModelAbstraction) {
            throw new WrongModelSetUpException(get_class($this->model));
        }

        $collection = collect();

        $collection->put('name', $this->model->pagHiperName());
        $collection->put('email', $this->model->pagHiperEmail());
        $collection->put('document', $this->model->pagHiperDocument());
        $collection->put('phone', $this->model->pagHiperPhone());
        $collection->put('address', $this->model->pagHiperAddress());

        return $this->map($collection->toArray());
    }

    private function map(array $data): array
    {
        return [
            'payer_email'      => data_get($data, 'email'),
            'payer_name'       => data_get($data, 'name'),
            'payer_cpf_cnpj'   => data_get($data, 'document'),
            'payer_phone'      => data_get($data, 'phone'),
            'payer_street'     => data_get($data, 'address.street'),
            'payer_number'     => data_get($data, 'address.number'),
            'payer_complement' => data_get($data, 'address.complement'),
            'payer_district'   => data_get($data, 'address.district'),
            'payer_city'       => data_get($data, 'address.city'),
            'payer_zip_code'   => data_get($data, 'address.zip_code'),
        ];
    }
}
