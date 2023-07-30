<?php

namespace DevAjMeireles\PagHiper\DTO\Objects;

use DevAjMeireles\PagHiper\Traits\MakeableObject;
use Illuminate\Contracts\Support\Arrayable;

class Address implements Arrayable
{
    use MakeableObject;

    public function __construct(
        private readonly string $street,
        private readonly string $number,
        private readonly string $complement,
        private readonly string $district,
        private readonly string $city,
        private readonly string $state,
        private readonly string $zip_code,
    ) {
        //
    }

    public function street(): string
    {
        return $this->street;
    }

    public function number(): string
    {
        return $this->number;
    }

    public function complement(): string
    {
        return $this->complement;
    }

    public function district(): string
    {
        return $this->district;
    }

    public function city(): string
    {
        return $this->city;
    }

    public function state(): string
    {
        return $this->state;
    }

    public function zipCode(): string
    {
        return $this->zip_code;
    }

    public function toArray(): array
    {
        return [
            'payer_street'     => $this->street(),
            'payer_number'     => $this->number(),
            'payer_complement' => $this->complement(),
            'payer_district'   => $this->district(),
            'payer_city'       => $this->city(),
            'payer_state'      => $this->state(),
            'payer_zip_code'   => $this->zipCode(),
        ];
    }
}
