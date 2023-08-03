<?php

namespace DevAjMeireles\PagHiper\DTO\Objects;

use DevAjMeireles\PagHiper\Traits\MakeableObject;
use Illuminate\Contracts\Support\Arrayable;

class Item implements Arrayable
{
    use MakeableObject;

    public function __construct(
        private readonly ?string $item_id = null,
        private readonly ?string $description = null,
        private readonly ?int $quantity = null,
        private readonly ?int $price_cents = null,
    ) {
        //
    }

    public function toArray(): array
    {
        return [
            'item_id'     => $this->item_id,
            'description' => $this->description,
            'quantity'    => $this->quantity,
            'price_cents' => $this->price_cents,
        ];
    }
}
