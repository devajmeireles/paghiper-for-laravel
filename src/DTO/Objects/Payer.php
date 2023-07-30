<?php

namespace DevAjMeireles\PagHiper\DTO\Objects;

use DevAjMeireles\PagHiper\Traits\MakeableObject;
use Illuminate\Contracts\Support\Arrayable;

class Payer implements Arrayable
{
    use MakeableObject;

    public function __construct(
        private readonly string $name,
        private readonly string $email,
        private readonly string $document,
        private readonly string $phone,
    ) {
        //
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function document(): string
    {
        return $this->document;
    }

    public function phone(): string
    {
        return $this->phone;
    }

    public function toArray(): array
    {
        return [
            'payer_name'     => $this->name(),
            'payer_email'    => $this->email(),
            'payer_cpf_cnpj' => $this->document(),
            'payer_phone'    => $this->phone(),
        ];
    }
}
