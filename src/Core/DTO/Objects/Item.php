<?php

namespace DevAjMeireles\PagHiper\Core\DTO\Objects;

use Illuminate\Contracts\Support\Arrayable;

class Item implements Arrayable
{
    public function __construct(
        private readonly int $id,
        private readonly string $description,
        private readonly int $quantity,
        private readonly int $price,
    ) {
        //
    }

    public function id(): int
    {
        return $this->id;
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
        return $this->price;
    }

    public function toArray(): array
    {
        return [
            'id'          => $this->id(),
            'description' => $this->description(),
            'quantity'    => $this->quantity(),
            'price'       => $this->price(),
        ];
    }
}
