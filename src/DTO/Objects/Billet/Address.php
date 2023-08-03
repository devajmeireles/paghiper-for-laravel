<?php

namespace DevAjMeireles\PagHiper\DTO\Objects\Billet;

use DevAjMeireles\PagHiper\Traits\MakeableObject;
use Illuminate\Contracts\Support\Arrayable;

class Address implements Arrayable
{
    use MakeableObject;

    public function __construct(
        private readonly ?string $street = null,
        private readonly ?string $number = null,
        private readonly ?string $complement = null,
        private readonly ?string $district = null,
        private readonly ?string $city = null,
        private readonly ?string $state = null,
        private readonly ?string $zip_code = null,
    ) {
        //
    }

    public function toArray(): array
    {
        return [
            'payer_street'     => $this->street,
            'payer_number'     => $this->number,
            'payer_complement' => $this->complement,
            'payer_district'   => $this->district,
            'payer_city'       => $this->city,
            'payer_state'      => $this->state,
            'payer_zip_code'   => $this->zip_code,
        ];
    }
}
