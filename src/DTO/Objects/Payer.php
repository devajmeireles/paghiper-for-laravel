<?php

namespace DevAjMeireles\PagHiper\DTO\Objects;

use DevAjMeireles\PagHiper\DTO\Objects\Billet\Address;
use DevAjMeireles\PagHiper\Traits\MakeableObject;
use Illuminate\Contracts\Support\Arrayable;

class Payer implements Arrayable
{
    use MakeableObject;

    public function __construct(
        private ?string $name = null,
        private ?string $email = null,
        private ?string $cpf_cnpj = null,
        private ?string $phone = null,
        private ?Address $address = null,
    ) {
        //
    }

    public function toArray(bool $address = true): array
    {
        $address = $this->address && $address ? [...$this->address->toArray()] : [];

        return [
            'payer_name'     => $this->name,
            'payer_email'    => $this->email,
            'payer_cpf_cnpj' => $this->cpf_cnpj,
            'payer_phone'    => $this->phone,
            ...$address,
        ];
    }
}
