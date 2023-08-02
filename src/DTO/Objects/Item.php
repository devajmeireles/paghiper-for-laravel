<?php

namespace DevAjMeireles\PagHiper\DTO\Objects;

use DevAjMeireles\PagHiper\Traits\MakeableObject;
use Illuminate\Contracts\Support\Arrayable;

//TODO: item vai ser compartilhado entre boleto e PIX
class Item implements Arrayable
{
    use MakeableObject;

    public function __construct(
        private ?string $item_id = null,
        private ?string $description = null,
        private ?int $quantity = null,
        private ?int $price_cents = null,
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
