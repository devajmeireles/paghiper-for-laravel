<?php

namespace DevAjMeireles\PagHiper\DTO\Objects;

use DevAjMeireles\PagHiper\Traits\MakeableObject;
use Illuminate\Contracts\Support\Arrayable;

class Item implements Arrayable
{
    use MakeableObject;

    public function __construct(
        private readonly int|string $item_id,
        private readonly string $description,
        private readonly int $quantity,
        private readonly int $price_cents,
    ) {
        //
    }

    public function id(): string
    {
        return (string) $this->item_id;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function price(): int
    {
        return $this->price_cents;
    }

    public function toArray(): array
    {
        return [
            'item_id'     => $this->id(),
            'description' => $this->description(),
            'quantity'    => $this->quantity(),
            'price_cents' => $this->price(),
        ];
    }
}
